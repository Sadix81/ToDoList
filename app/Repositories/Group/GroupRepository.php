<?php

namespace App\Repositories\Group;

use App\Models\Group\Group;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupRepository implements GroupRepositoryInterface
{
    public function index()
    {
        $owner = Auth::id();

        $req = [
            'sort' => request()->has('sort') ? request('sort') : 'updated_at',
            'order' => request()->has('order') ? request('order') : 'desc',
            'limit' => request()->has('limit') ? request('limit') : '25',
            'search' => request()->has('search') ? request('search') : null,
        ];

        $group = Group::where('owner_id', $owner)
            ->where(function ($query) use ($req) {
                if ($req['search']) {
                    $query->where('name', 'like' , '%' . $req['search'] . '%');
                }
            })
            ->orderBy($req['sort'], $req['order'])
            ->paginate($req['limit']);

        return $group;
    }

    public function store($request)
    {
        $auth = Auth::id();
        $usernames = $request->input('username', []); 

        DB::beginTransaction();
        try {
            $group = Group::create([
                'name' => $request->name,
                'owner_id' => $request->owner_id ?: $auth,
            ]);
            if (!empty($usernames)) {
                foreach ($usernames as $username) {
                    $members = User::where('username', $username)->get();
    
                    foreach ($members as $member) {
                        $group->users()->attach($member->id);
                    }
                }
            }
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update($group, $request)
    {
        $owner = Auth::id();

        DB::beginTransaction();
        try {
            $group->update([
                'name' => $request->name,
                'owner_id' => $owner,
            ]);

        
            if ($request->has('username')) {
                $usernames = $request->input('username', []); 

                $members = User::whereIn('username', $usernames)->get();
    
                $existingUserIds = $group->users()->pluck('users.id')->toArray();
    
                $newUserIds = $members->pluck('id')->toArray();
                $allUserIds = array_unique(array_merge($existingUserIds, $newUserIds));
    
                $group->users()->sync($allUserIds);
            }
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete($group)
    {
        try {
            $group->delete();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}

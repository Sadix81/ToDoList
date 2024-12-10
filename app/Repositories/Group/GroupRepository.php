<?php

namespace App\Repositories\Group;

use App\Models\Group\Group;
use App\Models\User;
use App\Models\UserRole\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

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
                    $query->where('name', 'like', '%'.$req['search'].'%');
                }
            })
            ->orderBy($req['sort'], $req['order'])
            ->paginate($req['limit']);

        return $group;
    }

    public function store($request)
    {
        $userId = Auth::id();

        $usernames = $request->input('user_id', []);

        $adminRole = Role::findByName('admin', 'api');
        $memberRole = Role::findByName('member', 'api');

        DB::beginTransaction();
        try {
            $group = Group::create([
                'name' => $request->name,
                'owner_id' => $request->owner_id ?: $userId,
            ]);
            //add the user who created the group to pivote_table as admin
            $userRole = new UserRole;
            $userRole->user_id = $userId;
            $userRole->group_id = $group->id;
            $userRole->role_id = $adminRole->id;
            $userRole->save();

            foreach ($usernames as $member) {
                $userToAdd = User::where('id', $member)->first();
                if ($userToAdd) {
                    $newUserRole = new UserRole;
                    $newUserRole->user_id = $userToAdd->id;
                    $newUserRole->group_id = $group->id;
                    $newUserRole->role_id = $memberRole->id;
                    $newUserRole->save();
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function update($group, $request)
    {
        $owner = Auth::id();
        $usernames = $request->input('user_id', []);
        $memberRole = Role::findByName('member', 'api');

        DB::beginTransaction();
        try {
            $group->update([
                'name' => $request->name,
                'owner_id' => $owner,
            ]);
            foreach ($usernames as $username) {
                $member = User::where('id', $username)->first();
                if ($member) {
                    $newUserRole = new UserRole;
                    $newUserRole->user_id = $member->id;
                    $newUserRole->group_id = $group->id;
                    $newUserRole->role_id = $memberRole->id;
                    $newUserRole->save();
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete($group)
    {
        DB::beginTransaction();
        try {

            UserRole::where('group_id', $group->id)->delete();
            $group->delete();

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function detached_user($group, $request)
    {

        $usernames = $request->input('user_id', []);

        DB::beginTransaction();

        try {
            //returns all user from specific group_id
            $userIds = UserRole::where('group_id', $group->id)->pluck('user_id')->toArray();

            foreach ($usernames as $username) {
                $user = User::where('id', $username)->first();
                if (! in_array($user->id, $userIds)) {
                    return response()->json(['error' => "کاربر '$username' در گروه یافت نشد"], 404);
                }

                UserRole::where('user_id', $user->id)
                    ->where('group_id', $group->id)
                    ->delete();
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}

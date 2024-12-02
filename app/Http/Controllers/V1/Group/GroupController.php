<?php

namespace App\Http\Controllers\V1\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\CreateGroupRequest;
use App\Http\Requests\Group\DetachUserRequest;
use App\Http\Requests\Group\UpdateGroupRequest;
use App\Http\Resources\Group\IndexGroupResource;
use App\Http\Resources\Group\ShowGroupResource;
use App\Models\Group\Group;
use App\Repositories\Group\GroupRepository;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    private $groupRepo;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepo = $groupRepository;
    }

    public function index()
    {
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')]);
        }

        return IndexGroupResource::collection($this->groupRepo->index());
    }

    public function store(CreateGroupRequest $request){
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')]);
        }
        
        if ($request->has('username') && is_array($request->username)) {
            $count = count($request->username);
            if ($count >= 5) {
                return response()->json(['message' => 'مجاز به اضافه کردن 4 نفر هستید'], 400); // 400 Bad Request
            }
        }

        $error = $this->groupRepo->store($request);
        
        if($error === null){
            return response()->json(['message' => __('messages.group.create.success' , ['name' => $request->name])] , 201);
        }
        return response()->json(['message' => __('messages.group.create.failed' , ['name' => $request->name])] , 500);
    }

    public function show(Group $group){
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')]);
        }

        return new ShowGroupResource($group);
    }

    public function update(Group $group , UpdateGroupRequest $request){
        $auth = Auth::user();
        $group_owner = $group->owner_id;

        if (! $auth) {
            return response()->json(['message' => __('messages.user.Inaccessibility')]);
        }

        if ($auth->id !== $group_owner) {
            return 'عدم دسترسی به گروه مورد نظر';
        }

        // Check the users of the group
        $groupUsers = $group->userRoles()->get()->pluck('id');
        $allUsers = $groupUsers; //containe all users from the group which we find it
        $allUsers[] = $auth->id;
        if(count($allUsers) >= 5){
            return response()->json(['message' => 'تکمیل ظرفیت گروه']);
        }

        $error = $this->groupRepo->update($group , $request);
        
        if($error === null){
            return response()->json(['message' => __('messages.group.update.success' , ['name' => $request->name])] , 200);
        }
        return response()->json(['message' => __('messages.group.update.failed' , ['name' => $request->name])] , 500);
    }

    public function destroy(Group $group){
        $auth = Auth::user();
        $group_owner = $group->owner_id;

        if (! $auth) {
            return response()->json(['message' => __('messages.user.Inaccessibility')]);
        }

        if ($auth->id != $group_owner) {
            return response()->json(['message' => 'عدم دسترسی به گروه مورد نظر']);
        }


        $error = $this->groupRepo->delete($group);
        if ($error === null) {
            return response()->json(['message' => __('messages.group.delete.success')], 200);
        }
        return response()->json(['message' => __('messages.group.delete.failed')], 500);

    }

    public function detached_user(Group $group , DetachUserRequest $request){
        $auth = Auth::user();
        $group_owner = $group->owner_id;

        if (! $auth) {
            return response()->json(['message' => __('messages.user.Inaccessibility')]);
        }

        if ($auth->id !== $group_owner) {
            return response()->json(['message' => 'عدم دسترسی به گروه مورد نظر']);
        }

        if (in_array($auth->username, $request->username)) {
            return response()->json(['error' => 'آدمین نمیتواند خود را حدف کند'], 403); // Admin cannot remove themselves
        }

        $error = $this->groupRepo->detached_user($group , $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.user.remove.success')], 200);
        }
        return response()->json(['message' => __('messages.user.remove.failed')], 500);

    }


}

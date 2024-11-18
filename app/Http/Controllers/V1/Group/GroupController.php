<?php

namespace App\Http\Controllers\V1\Group;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\CreateGroupRequest;
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
            return 'عدم دسترسی';
        }

        return IndexGroupResource::collection($this->groupRepo->index());
    }

    public function store(CreateGroupRequest $request){
        $user = Auth::id();

        if (! $user) {
            return 'عدم دسترسی';
        }
        
        if(count($request->username) >= 5){
            return response()->json(['message' => 'مجاز به اضافه کردن 4 نفر هستید']);
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
            return 'عدم دسترسی';
        }

        return new ShowGroupResource($group);
    }

    public function update(Group $group , UpdateGroupRequest $request){
        $auth = Auth::id();

        if (! $auth) {
            return 'عدم دسترسی';
        }

        // Check the users of the group
        $groupUsers = $group->users()->get()->pluck('id');
        $allUsers = $groupUsers; //containe all users from the group which we find it
        $allUsers[] = $auth;
        if(count($allUsers) > 6){
            return response()->json(['message' => 'تکمیل ظرفیت گروه']);
        }

        $error = $this->groupRepo->update($group , $request);
        
        if($error === null){
            return response()->json(['message' => __('messages.group.update.success' , ['name' => $request->name])] , 200);
        }
        return response()->json(['message' => __('messages.group.update.failed' , ['name' => $request->name])] , 500);
    }

    public function destroy(Group $group){
        $error = $this->groupRepo->delete($group);
        if ($error === null) {
            return response()->json(['message' => __('messages.group.delete.success')], 200);
        }
        return response()->json(['message' => __('messages.group.delete.failed')], 500);

    }


}

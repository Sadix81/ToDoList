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
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        return IndexGroupResource::collection($this->groupRepo->index());
    }

    public function store(CreateGroupRequest $request)
    {
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        if($request->has('user_id') && in_array($user , $request->input('user_id'))){
            return response()->json(['message' => 'مجاز به اضافه کردن کجدد خود نیستید'], 403);
        }

        if (count($request->input('user_id')) > count(array_unique($request->input('user_id')))) {
            return response()->json(['message' => 'نمیتوانید یک کاربر را دو بار اضافه کنید'], 409);
        }

        if ($request->has('user_id') && is_array($request->user_id)) {
            $count = count($request->user_id);
            if ($count >= 5) {
                return response()->json(['message' => 'مجاز به اضافه کردن 4 نفر هستید'], 400); // 400 Bad Request
            }
        }

        $error = $this->groupRepo->store($request);

        if ($error === null) {
            return response()->json(['message' => __('messages.group.create.success', ['name' => $request->name])], 201);
        }

        return response()->json(['message' => __('messages.group.create.failed', ['name' => $request->name])], 500);
    }

    public function show(Group $group)
    {
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        if ($group->owner_id !== $user) {
            return response()->json(['عدم دسترسی به گروه مورد نظر']);
        }

        return new ShowGroupResource($group);
    }

    public function update(Group $group, UpdateGroupRequest $request)
    {
        $user = Auth::id();
        $group_owner = $group->owner_id;

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        if ($user !== $group_owner) {
            return response()->json(['message' => 'عدم دسترسی به گروه مورد نظر']);
        }

        if($request->has('user_id') && in_array($user , $request->input('user_id'))){
            return response()->json(['message' => 'مجاز به اضافه کردن کجدد خود نیستید'], 403);
        }

        // Check the users of the group
        $groupUsers = $group->userRoles()->get()->pluck('user_id')->toArray();
        $allUsers = $groupUsers; //containe all users from the group which we find it
        $allUsers[] = $user;
        
        if (count($allUsers) > 6) {
            return response()->json(['message' => 'تکمیل ظرفیت گروه']);
        }
        if (array_intersect($allUsers , $request->user_id)) { //array_intersect => چک میکند که ایا ارایه ای که میفرستیم در ارایه ای که داریم هست یا نه
            return response()->json(['message' => 'شخص مورد نظر در حال حاضر عضو هست'] , 409);
        }

        $error = $this->groupRepo->update($group, $request);

        if ($error === null) {
            return response()->json(['message' => __('messages.group.update.success', ['name' => $request->name])], 200);
        }

        return response()->json(['message' => __('messages.group.update.failed', ['name' => $request->name])], 500);
    }

    public function destroy(Group $group)
    {
        $auth = Auth::user();
        $group_owner = $group->owner_id;

        if (! $auth) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
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

    public function detached_user(Group $group, DetachUserRequest $request)
    {
        $auth = Auth::user();
        $group_owner = $group->owner_id;

        if (! $auth) {
            return response()->json(['message' => __('messages.user.Inaccessibility')], 401);
        }

        if ($auth->id !== $group_owner) {
            return response()->json(['message' => 'عدم دسترسی به گروه مورد نظر']);
        }

        if (in_array($auth->id, $request->user_id)) {
            return response()->json(['error' => 'آدمین نمیتواند خود را حدف کند'], 403); // Admin cannot remove themselves
        }

        $error = $this->groupRepo->detached_user($group, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.user.remove.success')], 200);
        }

        return response()->json(['message' => __('messages.user.remove.failed')], 500);

    }
}

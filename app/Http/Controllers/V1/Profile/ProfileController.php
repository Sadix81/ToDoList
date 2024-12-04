<?php

namespace App\Http\Controllers\V1\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdatePasswordrequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\Profile\ShowProfileResource;
use App\Models\User;
use App\Repositories\Profile\ProfileRepository;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $profielRepo;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profielRepo = $profileRepository;
    }

    public function show(User $user) {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')] , 401);
        }
        return new ShowProfileResource($user);
    }

    public function update(User $user, UpdateProfileRequest $request)
    {

        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')] , 401);
        }

        $error = $this->profielRepo->update($user, $request);
        if ($error === null) {
            return response()->json(['message' => __('messages.user.profile.update.success')], 200);
        }

        return response()->json(['message' => __('messages.user.profile.update.failed')], 500);
    }

    public function changePassword(UpdatePasswordrequest $request){
        
        $user = Auth::user();
        
        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')] , 401);
        }

        //check if our current pass is correct or nou
        if (! password_verify($request->currentpassword, $user->password)) {
            return response()->json(['message' => 'رمز وارد شده نادرست است']);
        }

        // check if the new password isnt similar with current one
        if ($request->newpassword === $request->currentpassword) {
            return response()->json(['message' => 'رمز جدید باید متفاوت از پسورد فعلی باشد']);
        }

        $error = $this->profielRepo->changePassword($request);
        if ($error === null) {
            return response()->json(['message' => __('messages.user.password.update.success')], 200);
        }

        return response()->json(['message' => __('messages.user.password.update.failed')], 500);
    }
}

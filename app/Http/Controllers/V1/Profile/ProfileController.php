<?php

namespace App\Http\Controllers\V1\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\User;
use App\Repositories\Profile\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $profielRepo;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profielRepo = $profileRepository;
    }

    public function update(User $user , UpdateProfileRequest $request){

        $user = Auth::user();

        if(! $user){
            return 'عدم دسترسی کاربر' ;
        }

        $error = $this->profielRepo->update($user , $request);
        if($error === null){
            return response()->json(['message' => __('messages.user.profile.update.success')] , 200);
        }
        return response()->json(['message' => __('messages.user.profile.update.failed')] , 500);
    }
}

<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SearchUserRequest;
use App\Http\Resources\User\SearchUserResource;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $userRepo;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepo = $userRepository;
    }

    public function user_list(SearchUserRequest $request){
        $user = Auth::id();

        if (! $user) {
            return response()->json(['message' => __('messages.user.Inaccessibility')] , 401);
        }

        $users = $this->userRepo->user_list($request);

        return SearchUserResource::collection($users);
    }
}

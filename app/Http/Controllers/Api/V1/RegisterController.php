<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(RegisterRequest $registerRequest)
    {
        $user = User::create(array_merge(
            $registerRequest->validated(),
            [
                'password' => bcrypt($registerRequest->password),
                'role' => User::ROLE_CUSTOMER
            ]
        ));

        return UserResource::make($user);
    }
}

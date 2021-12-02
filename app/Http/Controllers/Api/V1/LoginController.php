<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Laundry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => 'Akun tidak terdaftar'
            ]);
        }

        if ($user) {
            $laundry = Laundry::query()
                ->whereBelongsTo($user)
                ->first();
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('laundream')->plainTextToken,
            'error' => null,
            'laundry' => $user->role != User::ROLE_CUSTOMER ? $laundry : null
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true
        ]);
    }
}

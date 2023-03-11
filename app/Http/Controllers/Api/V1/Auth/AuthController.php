<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ForgetPasswordAuthRequest;
use App\Http\Requests\V1\LoginAuthRequest;
use App\Http\Requests\V1\RegisterAuthRequest;
use App\Http\Requests\V1\ResetPasswordAuthRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{

    public function login(LoginAuthRequest $request)
    {

        $credentials = $request->validated();
        $token = Auth::attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(RegisterAuthRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole(['user']);

        $token = Auth::login($user);
        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function forgotPassword(ForgetPasswordAuthRequest $request)
    {
        $response = Password::sendResetLink($request->validated());

        return $response == Password::RESET_LINK_SENT
            ? response()->json(['success' => true])
            : response()->json(['error' => 'Failed to send reset link'], 500);
    }

    public function resetpassword(ResetPasswordAuthRequest $request)
    {
        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);

                $user->save();
            }
        );

        return $response == Password::PASSWORD_RESET
            ? response()->json(['success' => true])
            : response()->json(['error' => 'Failed to reset password'], 500);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}

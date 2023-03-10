<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\V1\UpdateNameEmailUserRequest;
use App\Http\Requests\v1\UpdatePasswordUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->can('view my profil') && !$user->can('view all profil')) {
            $users = User::find($user->id);
            return response()->json([
                'status' => 'success',
                'users' => $users
            ]);
        }
        $users = User::orderBy('id')->get();

        return response()->json([
            'status' => 'success',
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateNameEmail(UpdateNameEmailUserRequest $request, User $user)
    {
        $userauth = Auth::user();
        if ($userauth->can('edit my profil') && $userauth->id != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You dont have permission to Update tis user'
            ], 200);
        }

        $user->update($request->validated());

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "User Updated successfully!",
            'user' => $user
        ], 200);
    }
    public function updatePassword(UpdatePasswordUserRequest $request, User $user)
    {

        $userauth = Auth::user();

        if ($userauth->can('edit my profil') && !$userauth->can('edit all profil') && $userauth->id != $user->id) {
            return response()->json([
                'status' => true,
                'message' => 'You dont have permission to Update tis user'
            ], 200);
        }
        $user->update([
            'password' => Hash::make($request->validated())
        ]);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "User Updated successfully!",
            'user' => $user
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $userauth = Auth::user();
        if (!$userauth->can('delete all profil') && $userauth->id != $user->id) {
            return response()->json([
                'status' => true,
                'message' => 'You dont have permission to delete tis user'
            ], 200);
        }
        $user->delete();
        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ], 200);
    }
}

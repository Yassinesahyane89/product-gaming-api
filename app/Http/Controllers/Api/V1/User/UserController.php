<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ChangeRoleRequest;
use App\Http\Requests\V1\UpdateNameEmailUserRequest;
use App\Http\Requests\v1\UpdatePasswordUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

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
        if (!$user->can('view all profil')) {
            return response()->json([
                'status' => true,
                'message' => 'User retrieved successfully!',
                'data' => new UserResource($user),
            ], Response::HTTP_OK);
        }
        return response()->json([
            'status' => true,
            'message' => 'Users retrieved successfully!',
            'data' => UserResource::collection(User::all()),
        ], Response::HTTP_OK);
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
        if (!$userauth->can('edit all profil') && $userauth->id != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You dont have permission to Update this user'
            ], Response::HTTP_FORBIDDEN);
        }

        $user->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => "User updated successfully!",
            'data' => new UserResource($user)
        ], Response::HTTP_OK);
    }

    public function updatePassword(UpdatePasswordUserRequest $request, User $user)
    {

        $userauth = Auth::user();

        if (!$userauth->can('edit all profil') && !$userauth->can('edit all profil') && $userauth->id != $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'You dont have permission to Update this user'
            ], Response::HTTP_FORBIDDEN);
        }
        $user->update([
            'password' => Hash::make($request->validated())
        ]);

        return response()->json([
            'status' => true,
            'message' => "User updated successfully!",
            'data' => new UserResource($user)
        ], Response::HTTP_OK);
    }


    public function changeRole(ChangeRoleRequest $request,User $user){

        $user->syncRoles($request->validated());

        return response()->json([
            'status' => true,
            'message' => "User updated successfully!",
            'data' => new UserResource($user)
        ], Response::HTTP_OK);
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
                'status' => false,
                'message' => "You don't have permission to delete this user!",
            ], Response::HTTP_FORBIDDEN);
        }
        $user->delete();

         return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ], Response::HTTP_OK);
    }
}

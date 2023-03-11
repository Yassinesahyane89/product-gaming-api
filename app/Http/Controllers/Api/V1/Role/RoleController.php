<?php

namespace App\Http\Controllers\Api\V1\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreRoleRequest;
use App\Http\Requests\V1\UpdateRoleRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();

        $data = [];
        foreach ($roles as $role) {
            $data[] = [
                'type' => 'roles',
                'id' => $role->id,
                'attributes' => [
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name')
                ]
            ];
        }

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permission_ids);

        return response()->json([
            'data' => [
                'type' => 'roles',
                'id' => $role->id,
                'attributes' => [
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name')
                ]
            ],
            'message' => "Role created successfully!"
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return response()->json([
            'data' => [
                'type' => 'roles',
                'id' => $role->id,
                'attributes' => [
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name')
                ]
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permission_ids);

        return response()->json([
            'data' => [
                'type' => 'roles',
                'id' => $role->id,
                'attributes' => [
                    'name' => $role->name,
                    'permissions' => $role->permissions->pluck('name')
                ]
            ],
            'message' => "Role updated successfully!"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'message' => "Role deleted successfully!"
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    //
    /**
     * @return JsonResponse
     */
    public function index()
    {
        return response()->json([
            'Permissions' => Permission::all()
        ], 201);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        return response()->json([
            'Permission' => Permission::findById($id)
        ], 201);
    }

    /**
     * @param Request $request
     * @param Permission $permission
     * @return JsonResponse
     */
    public function update(Request $request, Permission $permission)
    {
//        $permission->fill($request->all());
        if ($permission->update($request->all())) {
            return $this->response(['message' => "Permission updated successfully!", 'Permission' => $permission]);
        } else {
            return $this->errorResponse($request, 'Failed to update user', 500);
        }
    }

    /**
     * @param Permission $permission
     * @return JsonResponse
     */
    public function destroy(Permission $permission)
    {
        if ($permission->delete()) {
            return $this->response([
                'message' => "Permission delete successfully!",
                'Permission' => $permission
            ],200);

        } else {
            return $this->errorResponse(
                null,
                'Failed to delete Permission',
                500);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $validateData = $request->validate([
            "name" => "required|string"
        ]);

        $permission = Permission::create([
            'name' => $validateData['name']
        ]);

        return response()->json([
            'message' => "Permission Created successfully!",
            'Permission' => $permission
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function assignRole(Request $request)
    {
        $validateData = $request->validate([
            "permission_name" => "required|string",
            "role_name" => "required|string",
        ]);

        $permission = Permission::findByName($validateData['permission_name']);
        $role = Role::findByName($validateData['role_name']);

        $permission->assignRole($role);

        return response()->json([
            'message' => "Permission assigned to role successfully!",
            'Permission' => $permission,
            'Role' => $role,
        ], 200);
    }
}

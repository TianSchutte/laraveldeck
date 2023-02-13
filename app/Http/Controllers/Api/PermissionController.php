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
        $permissions = Permission::all();

        return response()->json([
            'Permissions' => $permissions
        ], 201);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $permission = Permission::findById($id);

        return response()->json([
            'Permission' => $permission
        ], 201);
    }

    /**
     * @param Request $request
     * @param Permission $permission
     * @return JsonResponse
     */
    public function update(Request $request, Permission $permission)
    {

        $permission->fill($request->all());
        $permission->save();

        return response()->json([
            'message' => "Permission Updated successfully!",
            'Permission' => $permission,
        ], 200);
    }

    /**
     * @param Permission $permission
     * @return JsonResponse
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json([
            'message' => "Permission Deleted successfully!",
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $validateData = $request->only("name");
        $permission = Permission::create(['name' => $validateData['name']]);

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
        $validateData = $request->only(["permission_name", "role_name"]);

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

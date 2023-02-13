<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    //
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $roles = Role::all();

        return response()->json([
            'Roles' => $roles
        ], 201);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $role = Role::findById($id);

        return response()->json([
            'Role' => $role
        ], 201);
    }

    /**
     * @param Request $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Role $role)
    {

        $role->fill($request->all());
        $role->save();

        return response()->json([
            'message' => "Role Updated successfully!",
            'Role' => $role,
        ], 200);
    }

    /**
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json([
            'message' => "Role Deleted successfully!",
        ], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validateData = $request->only("name");
        $role = Role::create(['name' => $validateData['name']]);

        return response()->json([
            'message' => "Role Created successfully!",
            'role' => $role
        ], 200);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function grantUserRole(Request $request, User $user)
    {
        $validateData = $request->only("role_name");

        $user->assignRole($validateData['role_name']);

        return response()->json([
            'message' => "Role Added successfully!",
            'role' => $user->getRoleNames()
        ], 200);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function revokeUserRole(Request $request, User $user)
    {
        $validateData = $request->only("role_name");

        $user->removeRole($validateData['role_name']);

        return response()->json([
            'message' => "Role Removed successfully!",
            'role' => $user->getRoleNames()
        ], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewRoleUsers(Request $request)
    {
        $validateData = $request->only("role_name");
        $users = User::role($validateData["role_name"])->get();

        return response()->json([
            'Role Users' => $users
        ], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignPermissions(Request $request)
    {
        $validateData = $request->only(["permission_name", "role_name"]);

        $permission = Permission::findByName($validateData['permission_name']);
        $role = Role::findByName($validateData['role_name']);

        $role->givePermissionTo($permission);

        return response()->json([
            'message' => "Permission assigned to role successfully!",
            'Permission' => $permission,
            'Role' => $role,
        ], 200);
    }

}

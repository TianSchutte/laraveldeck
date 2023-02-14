<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends ApiBaseController
{
    //
    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->response(['Roles' => Role::all()], 201);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        return $this->response(['Roles' => Role::findById($id)], 200);
    }

    /**
     * @param Request $request
     * @param Role $role
     * @return JsonResponse
     */
    public function update(Request $request, Role $role)
    {
        if ($role->update($request->all())) {
            return $this->response(['message' => "Role updated successfully!", 'User' => $user]);
        } else {
            return $this->errorResponse($request, 'Failed to update role', 500);
        }
    }

    /**
     * @param Role $role
     * @return JsonResponse
     */
    public function destroy(Role $role)
    {
        if ($role->delete()) {
            return $this->response(['message' => "Role delete successfully!", 'User' => $role], 200);

        } else {
            return $this->errorResponse(null, 'Failed to delete role', 500);
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

        return $this->response([
                'message' => "Role Created successfully!",
                'Role' => Role::create([
                    'name' => $validateData['name']
                ])
        ], 200);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function grantUserRole(Request $request, User $user)
    {
        $validateData = $request->validate([
            "role_name" => "required|string"
        ]);

        $user->assignRole($validateData['role_name']);

        return $this->response([
            'message' => "Role Added to User successfully!",
            'Role' => Role::create([
                'name' => $user->getRoleNames()
            ])
        ], 200);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function revokeUserRole(Request $request, User $user)
    {
        $validateData =  $request->validate([
            "role_name" => "required|string"
        ]);
        $user->removeRole($validateData['role_name']);

        return $this->response([
            'message' => "Role(s) Revoked to User successfully!",
            'Role' => Role::create([
                'name' => $user->getRoleNames()
            ])
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function viewRoleUsers(Request $request)
    {
        $validatedData = $request->validate([
            "role_name" => "required|string"
        ]);

        $roleName = $validatedData["role_name"];
        $roleUsers = User::role($roleName)->get();

        return $this->response([
            'Role Users' => $roleUsers
        ], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function assignPermissions(Request $request)
    {
        $validatedData = $request->validate([
            "permission_name" => "required",
            "role_name" => "required"
        ]);

        $permission = Permission::findByName($validatedData['permission_name']);
        if (!$permission) {
            return $this->response([
                'message' => "Permission not found!",
            ], 404);
        }

        $role = Role::findByName($validatedData['role_name']);
        if (!$role) {
            return $this->response([
                'message' => "Role not found!",
            ], 404);
        }

        $role->givePermissionTo($permission);

        return $this->response([
            'message' => "Permission assigned to role successfully!",
            'permission' => $permission,
            'role' => $role
        ], 200);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    //
    public function index(){
        $permissions = Permission::all();

        return response()->json([
            'Permissions'=> $permissions
        ], 201);
    }
    public function show($id){
        $permission= Permission::findById($id);

        return response()->json([
            'Permission'=> $permission
        ], 201);
    }
    public function update(Request $request, Permission $permission){

        $permission->fill($request->all());
        $permission->save();

        return response()->json([
            'message' => "Permission Updated successfully!",
            'Permission' => $permission,
        ], 200);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return response()->json([
            'message' => "Permission Deleted successfully!",
        ], 200);
    }

    public function create(Request $request){
        $validateData = $request->only("name");
        $permission = Permission::create(['name' =>$validateData['name']]);

        return response()->json([
            'message' => "Permission Created successfully!",
            'Permission'=>$permission
        ], 200);
    }

    public function assignRole(Request $request){
        $validateData = $request->only(["permission_name","role_name"]);

        $permission=Permission::findByName($validateData['permission_name']);
        $role=Role::findByName($validateData['role_name']);

        $permission->assignRole($role);

        return response()->json([
            'message' => "Permission assigned to role successfully!",
            'Permission'=>$permission,
            'Role'=>$role,
        ], 200);
    }
}

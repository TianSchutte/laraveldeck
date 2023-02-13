<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();

        return response()->json([
            'Users'=> $users
        ], 201);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['User' => $user], 200);
    }

    public function update(Request $request, User $user)
    {
        $user->fill($request->all());
        $user->save();

        return response()->json([
            'message' => "User Updated successfully!",
            'User' => $user,
        ], 200);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => "User Deleted successfully!",
        ], 200);
    }
}

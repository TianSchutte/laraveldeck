<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'Users'=> $users
        ], 201);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['User' => $user], 200);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $user->fill($request->all());
        $user->save();

        return response()->json([
            'message' => "User Updated successfully!",
            'User' => $user,
        ], 200);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => "User Deleted successfully!",
        ], 200);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function showUserAccessTokens(User $user){
        $access_tokens =
            DB::table('personal_access_tokens')
            ->where('tokenable_id','=',$user->id)
            ->get();

        return response()->json([
            'access_tokens' => $access_tokens,
        ], 200);
    }
}

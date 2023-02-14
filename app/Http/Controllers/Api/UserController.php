<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends ApiBaseController
{

    /**
     * @return JsonResponse
     */
    public function index()
    {
        return $this->response(['Users' => User::all()], 201);
    }

    /**
     * @param $id
     */
    public function show($id)
    {
        return $this->response(['User' => User::findOrFail($id)]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(Request $request, User $user)
    {
        if ($user->update($request->all())) {

            return $this->response([
                'message' => "User updated successfully!",
                'User' => $user
            ]);
        } else {

            return $this->errorResponse($request, 'Failed to update user', 500);
        }
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {

            return $this->response([
                'message' => "User delete successfully!",
                'User' => $user
            ], 200);
        } else {

            return $this->errorResponse(
                null,
                'Failed to delete user',
                500);
        }

    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function showUserAccessTokens(User $user)
    {
        try {
//        $access_tokens = $user->personalAccessTokens->where('tokenable_id', '=', $user->id);

            $access_tokens =
                DB::table('personal_access_tokens')
                    ->where('tokenable_id', '=', $user->id)
                    ->get();

            return $this->response(['access_tokens' => $access_tokens]);

        } catch (\Exception $e) {
            $errorMessage = "Failed to retrieve user access tokens. Error: " . $e->getMessage();

            return $this->errorResponse(null, $errorMessage, 500);
        }
    }
}

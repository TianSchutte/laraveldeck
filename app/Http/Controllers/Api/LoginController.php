<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $data = $request->only(["email", "password"]);

        if (Auth::attempt($data)) {
            $user = Auth::user();
            $token = $user->createToken('token')->accessToken;

            return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token->token,
                    'type' => 'bearer',
                ]
            ], 200);
        }

        return response()->json(['error' => 'Unauthorized ' . Auth::attempt($data)], 401);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'surname' => $validatedData['surname'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        return response()->json([
            'success' => 'User Registered',
            'user' => $user,
        ], 201);
    }
    //
    public function index(){
        return view('register');
    }

    public function loginView(){
        return view('login');
    }
}

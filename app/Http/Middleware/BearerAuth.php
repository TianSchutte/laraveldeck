<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use PHPUnit\Exception;

class BearerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $hasAccessToken = PersonalAccessToken::where('token', $request->bearerToken())->first();

        if (!isset($hasAccessToken)) {
            return response(['error' => 'Unauthorized'], 401);
        }

        if ($hasAccessToken->created_at->addMinutes(30)->isPast()) {
            $hasAccessToken->delete();
            return response(['error' => 'Access Token has expired'], 401);
        }

        //get user from tokenable_id (user id) and log that corresponding user in
        $user = User::find($hasAccessToken->tokenable_id);
        Auth::login($user);

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Log;
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
        try {

            $personalAccessToken = PersonalAccessToken::where(
                'token',
                $request->bearerToken()
            )->first();

            if (!isset($personalAccessToken)) {
                Log::error('Personal access token not found in database', [
                    'url' => $request->url(),
                    'header' => $request->header(),
                    'ip' => $request->ip(),
                ]);

                return response(['error' => 'Unauthorized'], 401);
            }

            if ($personalAccessToken->created_at->addMinutes(30)->isPast()) {
                $personalAccessToken->delete();
                Log::error('Middleware Failure: Personal access token has expired and will now be removed', [
                    'url' => $request->url(),
                    'header' => $request->header(),
                    'ip' => $request->ip(),
                ]);

                return response(['error' => 'Access Token has expired'], 401);
            }

            //get user from tokenable_id (user id) and log that corresponding user in
            $user = User::find($personalAccessToken->tokenable_id);
            Auth::login($user);

        } catch (Exception $e) {
            Log::error('Middleware Failure: ' . $e->getMessage(), [
                'url' => $request->url(),
                'header' => $request->header(),
                'ip' => $request->ip(),
            ]);

            return response(['error' => 'Access Token has expired'], 401);
        }

        return $next($request);
    }
}

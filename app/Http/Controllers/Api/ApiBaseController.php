<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class ApiBaseController extends Controller
{
    /**
     * @param array $data
     * @param int $status
     * @return JsonResponse
     */
    protected function response(array $data, int $status = 200): JsonResponse
    {
        return response()->json($data, $status);
    }

    /**
     * @param Request $request
     * @param string $message
     * @param int $status
     * @return JsonResponse
     */
    protected function errorResponse(Request $request, string $message, int $status = 400)
    {
        Log::error($message, [
            'url' => $request->url(),
            'header' => $request->header(),
            'ip' => $request->ip(),
        ]);
        return response()->json(['error' => $message], $status);
    }
}

<?php

declare(strict_types=1);

namespace N8nAutomation\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use N8nAutomation\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractApiController extends Controller
{
    public function successResponse($data = [], $message = '', $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $code);
    }

    public function errorResponse($message = '', $code = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], $code);
    }
}

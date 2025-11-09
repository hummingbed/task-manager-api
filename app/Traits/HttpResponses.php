<?php
namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HttpResponses
{
    protected function successHttpMessage($data, $message, $code): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorHttpMessage($code, $message = null): JsonResponse
    {
        return response()->json([
            'status' => 'failed',
            'message' => $message,
            'data' => null
        ], $code);
    }
}
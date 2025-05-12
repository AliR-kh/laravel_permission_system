<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public static function ApiResponse(int $code = null, string $messages = null, array $data = [], $status = "Not OK", $error = null, int $error_code = null): JsonResponse
    {
        return response()->json([
            'status' => $status,
            'messages' => $messages,
            'data' => $data,
            'error' => [
                'error_messages' => $error,
                'error_code' => $error_code
            ],
        ])->header('Content-Type', 'application/json')->setStatusCode($code);
    }

    public static function successResponse(string $message = "success", $data =[], $status = "OK", $error = null, $error_code = null): JsonResponse
    {
        return self::ApiResponse(200, $message, $data, $status, $error, $error_code);
    }

    // 400 - Bad Request
    public static function badRequest(string $message = "Bad Request", $data =[], $status = "Not OK", $error = null, $error_code = null): JsonResponse
    {
        return self::ApiResponse(400, $message, $data, $status, $error, $error_code);
    }

    // 401 - Unauthorized
    public static function unauthorized(string $message = "Unauthorized", $data =[], $status = "Not OK", $error = null, $error_code = null): JsonResponse
    {
        return self::ApiResponse(401, $message, $data, $status, $error, $error_code);
    }

    // 403 - Forbidden
    public static function forbidden(string $message = "Forbidden", $data =[], $status = "Not OK", $error = null, $error_code = null): JsonResponse
    {
        return self::ApiResponse(403, $message, $data, $status, $error, $error_code);
    }

    // 404 - Not Found
    public static function notFound(string $message = "Resource not found", $data =[], $status = "Not OK", $error = null, $error_code = null): JsonResponse
    {
        return self::ApiResponse(404, $message, $data, $status, $error, $error_code);
    }

    // 405 - Method Not Allowed
    public static function methodNotAllowed(string $message = "Method not allowed", $data =[], $status = "Not OK", $error = null, $error_code = null): JsonResponse
    {
        return self::ApiResponse(405, $message, $data, $status, $error, $error_code);
    }

    // 408 - Request Timeout
    public static function requestTimeout(string $message = "Request timeout", $data =[], $status = "Not OK", $error = null, $error_code = null): JsonResponse
    {
        return self::ApiResponse(408, $message, $data, $status, $error, $error_code);
    }

    // 409 - Conflict
    public static function conflict(string $message = "Conflict in request", $data =[], $status = "Not OK", $error = null, $error_code = null): JsonResponse
    {
        return self::ApiResponse(409, $message, $data, $status, $error, $error_code);
    }

    // 422 - Validation Error

    // 429 - Too Many Requests
    public static function tooManyRequests(string $message = "Too many requests", $data =[], $status = "Not OK", $error = null, $error_code = null): JsonResponse
    {
        return self::ApiResponse(429, $message, $data, $status, $error, $error_code);
    }

    // 500 - Internal Server Error
    public static function serverError(string $message = "Internal Server Error", $data =[], $status = "Not OK", $error = null, $error_code = null): JsonResponse
    {
        return self::ApiResponse(500, $message, $data, $status, $error, $error_code);
    }
}

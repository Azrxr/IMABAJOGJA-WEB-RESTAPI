<?php
namespace App\Helpers;

class ApiResponse{
    
    public static function jsonResponse(bool $error, string $message, $data = null, int $status = 200)
    {
        return response()->json([
            'error' => $error,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}
<?php

if (!function_exists('responseJson')) {
    function responseJson($type, $data = null, $message = null, $status = 200)
    {
        return match ($type) {
            'data' => response()->json([
                'data' => $data,
            ], $status),
            'message' => response()->json([
                'message' => $message
            ], $status),
            'dataAndMessage' => response()->json([
                'data' => $data,
                'message' => $message
            ], $status),
            default => response()->json([
                'message' => 'Bir hata oluştu, lütfen tekrar deneyin.'
            ], 500)
        };
    }
}

if (!function_exists('exceptionResponseJson')) {
    function exceptionResponseJson($message, $exceptionMessage)
    {
        return response()->json([
            'message' => $message,
            'exceptionMessage' => $exceptionMessage
        ], 500);
    }
}

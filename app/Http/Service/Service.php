<?php

namespace App\Http\Service;

// base service modal
class Service
{
    public function successResponse($status = "success", $message = "Request processed successfully", $data = [])
    {
        return [
            "status" => $status,
            "message" => $message,
            "data" => $data
        ];
    }

    public function errorResponse($status = "error", $message = "Request processing failed", $data = [])
    {
        return [
            "status" => $status,
            "message" => $message,
            "data" => $data
        ];
    }
}

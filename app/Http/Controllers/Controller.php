<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function processing($message){
        return  response()->json([
            "status" => "processing",
            "message" => $message
        ], 200);
    }
    public function success($message, $body, $httpcode)
    {
        return  response()->json([
            "success" => true,
            "msg" => $message,
            "data" => $body
        ],$httpcode);
    }
    public function error($error, $httpcode)
    {
        return  response()->json([
            "success" => false,
            "error" => $error
        ], $httpcode);
    }
}

<?php

namespace App\Traits;

trait ResponseTrait
{
    public function redirectNotify($type, $message, $route)
    {
        $notify[] = [$type, $message];
        return to_route($route)->withNotify($notify);
    }

    // ajax request response

    public function success($data)
    {
        return response()->json([
            "status"  => true,
            "message" => "Success",
            "data"    => $data
        ],200);
    }


    public function error()
    {
        return response()->json([
            "status"  => false,
            "message" => "Something went wrong.",
        ],500);
    }
}

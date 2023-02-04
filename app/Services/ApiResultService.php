<?php

namespace App\Services;


class ApiResultService
{
    public static function success($data) {
        $res = [
            'status' => true,
            'result' => $data
        ];
        return response()->json( $res, 200);
    }

    public static function error($data) {
        $res = [
            'status' => false,
            'result' => $data
        ];

        return response()->json( $res, 500);
    }
 }
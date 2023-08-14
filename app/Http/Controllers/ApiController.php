<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * 成功返回.
     *
     * @param array $data
     * @param string $msg
     * @return JsonResponse
     */
    public function success(array $data = [], string $msg = "OK"): \Illuminate\Http\JsonResponse
    {
        $result = [
            "code" => 200,
            "msg" => $msg,
            "data" => $data,
        ];

        return response()->json($result, 200);
    }

    /**
     * 失败返回.
     *
     * @param string|int $code
     * @param array $data
     * @param string $msg
     * @return JsonResponse
     */
    public function error(string|int $code = "422", array $data = [], string $msg = "fail"): \Illuminate\Http\JsonResponse
    {
        $result = [
            "code" => $code,
            "msg" => $msg,
            "data" => $data,
        ];

        return response()->json($result, 200);
    }
}

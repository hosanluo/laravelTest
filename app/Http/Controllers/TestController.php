<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestRequest;
use App\Rules\Test;
use Exception;
use Illuminate\Http\Request;

class TestController extends ApiController
{

    public function returnContent(): \Illuminate\Http\JsonResponse
    {
        return $this->success(['request_content' => request()->all()]);
    }

    public function throwExpectedError(TestRequest $request): \Illuminate\Http\JsonResponse
    {
        $request->validated();
        return $this->success();
    }

    /**
     * @throws Exception
     */
    public function throwUnexpectedError(): \Illuminate\Http\JsonResponse
    {
        try {
            $c = $a + $b;
            return $this->success();
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }

    public function validateString(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            's' => ['required', 'string', 'min:1', 'max:10000', new Test()],
        ]);

        return $this->success();
    }
}

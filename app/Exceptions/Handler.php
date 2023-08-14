<?php

namespace App\Exceptions;

use App\Repositories\ErrorLog;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected bool $isRecord = false;

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->stopIgnoring(HttpException::class);

        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response|\Illuminate\Http\RedirectResponse
    {
        $response['data'] = [];
        $error = $this->convertExceptionToResponse($e);

        if ($request->is('api/*')) {
            $response['msg'] = $e->getMessage();
            if (config('app.debug')) {
                $response['status'] = $error->getStatusCode();
                $response['trace'] = $e->getTraceAsString();
                $response['code'] = $e->getCode();
            } else {
                $response['code'] = $error->getStatusCode();
                if ($e instanceof ValidationException) {
                    $response['msg'] = '请求验证失败';
                    $response['code'] = 422;
                } else if ($error->getStatusCode() == '404') {
                    $response['msg'] = '404“未找到”';
                } else if ($error->getStatusCode() >= 500) {
                    $response['code'] = 500;
                    $response['msg'] =  'Internal Server Error';
                    if (!$this->isRecord) {
                        $this->isRecord = true;
                        $errorLog = new ErrorLog();
                        $errorLog->create([
                            'code' => $e->getCode() ?: $error->getStatusCode(),
                            'status_code' => $error->getStatusCode(),
                            'msg' => $e->getMessage()
                        ]);
                    }
                }
            }
        } else {
            if (config('app.debug')) {
                $response['status'] = $error->getStatusCode();
                $response['trace'] = $e->getTraceAsString();
                $response['code'] = $e->getCode();
            } else {
                if ($error->getStatusCode() == '404') {
                    $response['msg'] = '404“未找到”';
                    $response['code'] = $e->getCode() ?: $error->getStatusCode();
                } else {
                    $response['msg'] = $e->getMessage();
                    $response['code'] = $e->getCode();
                }
            }
        }

        return response()->json($response, $error->getStatusCode());
//        return parent::render($request, $e);
    }
}

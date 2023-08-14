<?php

namespace App\Http\Middleware;

use App\Repositories\RequestLog;
use Closure;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class RequestLoggerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if (!$request->requestId) {
            $request->requestId = $this->createRequestId();
            $requestLog = new RequestLog();
            $requestLog->create([
                'request_id' => $request->requestId,
                'request_method' => $request->method(),
                'request_path' => $request->path(),
                'request_data' => $request->getContent(),
                'response_content' => $response->getContent(),
            ]);
        }

        return $next($request);
    }

    private function createRequestId(): string
    {
        return Uuid::uuid4()->toString();
    }
}

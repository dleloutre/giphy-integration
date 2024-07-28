<?php

namespace App\Http\Middleware;

use App\DTOs\RequestLogData;
use App\Services\RequestLogService;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    public function __construct(private readonly RequestLogService $requestLogService)
    {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        try {
            $requestLogDTO = RequestLogData::fromArray([
                'user_id' => auth()->check() ? auth()->id() : null,
                'service_name' => $request->path(),
                'request_body' => json_encode($request->all()),
                'status_code' => $response->getStatusCode(),
                'response_body' => $response->getContent(),
                'client_ip_address' => $request->ip()
            ]);
            $this->requestLogService->addRequestLog($requestLogDTO);
        } catch (Exception $e) {
            Log::error('Error while logging request', ['data' => $e->getMessage()]);
        }
    }
}

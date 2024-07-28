<?php

namespace App\Repositories;

use App\DTOs\RequestLogData;
use App\Models\RequestLog;
use App\Repositories\Interfaces\RequestLogRepositoryInterface;

class RequestLogRepository implements RequestLogRepositoryInterface
{
    public function __construct(private readonly RequestLog $requestLog)
    {}

    public function create(RequestLogData $requestLogDTO): void
    {
        $this->requestLog->create([
            'user_id' => $requestLogDTO->userId,
            'service_name' => $requestLogDTO->serviceName,
            'request_body' => $requestLogDTO->requestBody,
            'status_code' => $requestLogDTO->statusCode,
            'response_body' => $requestLogDTO->responseBody,
            'client_ip_address' => $requestLogDTO->clientIpAddress
        ]);
    }
}

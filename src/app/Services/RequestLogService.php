<?php

namespace App\Services;

use App\Repositories\RequestLogRepository;

class RequestLogService
{
    public function __construct(private readonly RequestLogRepository $requestLogRepository)
    {}

    /**
     * @param $requestLog
     * @return void
     */
    public function addRequestLog($requestLog): void
    {
        $this->requestLogRepository->create($requestLog);
    }
}
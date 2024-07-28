<?php

namespace App\Repositories\Interfaces;

use App\DTOs\RequestLogData;

interface RequestLogRepositoryInterface
{
    public function create(RequestLogData $requestLogDTO): void;
}
<?php

namespace App\DTOs;

class RequestLogData
{
    public function __construct(
        public ?int $userId,
        public string $serviceName,
        public string $requestBody,
        public int $statusCode,
        public string $responseBody,
        public string $clientIpAddress)
    {}

    public static function fromArray(array $data): RequestLogData
    {
        return new self(
            userId: $data['user_id'],
            serviceName: $data['service_name'],
            requestBody: $data['request_body'],
            statusCode: $data['status_code'],
            responseBody: $data['response_body'],
            clientIpAddress: $data['client_ip_address']
        );
    }
}
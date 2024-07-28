<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_name',
        'request_body',
        'response_body',
        'client_ip_address',
        'status_code'
    ];

    protected $casts = [
        'request_body' => 'json',
        'response_body' => 'json',
    ];
}

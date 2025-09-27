<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    protected $proxies = null; // or '*' if you trust your LB to set headers

    protected $headers = Request::HEADER_X_FORWARDED_AWS_ELB;
}
<?php

use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    protected $proxies = '*'; // hoặc IP cụ thể của proxy/nginx nếu dùng

    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
<?php

namespace coarnotify\http;

use coarnotify\http\HttpResponse;

class CurlHttpResponse implements HttpResponse
{
    private $headers;
    private $statusCode;

    public function __construct($statusCode, $headers)
    {
        $this->headers = $headers;
        $this->statusCode = $statusCode;
    }

    public function getHeader(string $headerName): ?string
    {
        return $this->headers[$headerName];
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
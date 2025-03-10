<?php

namespace Tests\mocks;

use coarnotify\http\HttpResponse;

class MockHttpResponse implements HttpResponse
{
    private $statusCode;
    private $location;

    public function __construct(int $statusCode = 200, ?string $location = null)
    {
        $this->statusCode = $statusCode;
        $this->location = $location;
    }

    public function getHeader(string $headerName): ?string
    {
        if (strtolower($headerName) === 'location') {
            return $this->location;
        }
        return null;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
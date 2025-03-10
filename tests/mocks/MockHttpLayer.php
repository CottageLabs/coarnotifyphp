<?php

namespace Tests\mocks;

use coarnotify\http\HttpLayer;
use coarnotify\http\HttpResponse;

class MockHttpLayer implements HttpLayer
{
    private $statusCode;
    private $location;

    public function __construct(int $statusCode = 200, ?string $location = null)
    {
        $this->statusCode = $statusCode;
        $this->location = $location;
    }

    public function post(string $url, string $data, ?array $headers = [], ...$args): MockHttpResponse
    {
        return new MockHttpResponse($this->statusCode, $this->location);
    }

    public function get(string $url, ?array $headers = [], ...$args): MockHttpResponse
    {
        throw new \BadMethodCallException("Not implemented");
    }
}
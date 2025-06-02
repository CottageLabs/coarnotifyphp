<?php

namespace coarnotify\http;

use coarnotify\http\HttpResponse;

/**
 * Implementation of the HTTP response using the CURL library.
 */
class CurlHttpResponse implements HttpResponse
{
    private $headers;
    private $statusCode;

    public function __construct($statusCode, $headers)
    {
        $this->headers = $headers;
        $this->statusCode = $statusCode;
    }

    /**
     * Get the value of a header from the response
     *
     * @param string $headerName
     * @return string|null
     */
    public function getHeader(string $headerName): ?string
    {
        return $this->headers[$headerName];
    }

    /**
     * Get the status code of the response
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
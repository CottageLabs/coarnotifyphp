<?php

namespace coarnotify\http;

interface HttpResponse
{
    /**
     * Get the value of a header from the response.
     *
     * @param string $headerName The name of the header
     * @return string The header value
     */
    public function getHeader(string $headerName): ?string;

    /**
     * Get the status code of the response.
     *
     * @return int The status code
     */
    public function getStatusCode(): int;
}
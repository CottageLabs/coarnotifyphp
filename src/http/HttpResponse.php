<?php

namespace coarnotify\http;

/**
 * Interface for HTTP response object
 *
 * This defines the methods which need to be implemented in order for the client to fully operate
 */
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
<?php

namespace coarnotify\http;

interface HttpLayer
{
    /**
     * Make an HTTP POST request to the supplied URL with the given body data, and headers.
     *
     * @param string $url The request URL
     * @param string $data The body data
     * @param array|null $headers HTTP headers as an associative array to include in the request
     * @param mixed ...$args Argument list to pass on to the implementation
     * @return HttpResponse
     */
    public function post(string $url, string $data, ?array $headers = [], ...$args): HttpResponse;

    /**
     * Make an HTTP GET request to the supplied URL with the given headers.
     *
     * @param string $url The request URL
     * @param array|null $headers HTTP headers as an associative array to include in the request
     * @param mixed ...$args Argument list to pass on to the implementation
     * @return HttpResponse
     */
    public function get(string $url, ?array $headers = [], ...$args): HttpResponse;
}
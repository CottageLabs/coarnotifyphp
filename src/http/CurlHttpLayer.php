<?php

namespace coarnotify\http;

use coarnotify\http\HttpLayer;
use coarnotify\http\CurlHttpResponse;

/**
 * Implementation of the HTTP layer using the CURL library.  This is the default implementation used
 * when no other implementation is supplied
 */
class CurlHttpLayer implements HttpLayer
{
    /**
     * Make an HTTP POST request to the supplied URL with the given body data, and headers
     *
     * @param string $url
     * @param string $data
     * @param array|null $headers
     * @param ...$args
     * @return \coarnotify\http\CurlHttpResponse
     * @throws \Exception
     */
    public function post(string $url, string $data, ?array $headers = [], ...$args): CurlHttpResponse
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge([
            'Content-Type: application/json',
        ], $headers));
        curl_setopt($ch, CURLOPT_HEADER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }

        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        $headers = $this->parseHeaders($headers);

        curl_close($ch);

        return new CurlHttpResponse($httpStatusCode, $headers);
    }

    /**
     * Make an HTTP GET request to the supplied URL with the given headers
     *
     * @param string $url
     * @param array|null $headers
     * @param ...$args
     * @return \coarnotify\http\CurlHttpResponse
     * @throws \Exception
     */
    public function get(string $url, ?array $headers = [], ...$args): CurlHttpResponse
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HEADER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception('cURL error: ' . curl_error($ch));
        }

        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        $headers = $this->parseHeaders($headers);

        curl_close($ch);

        return new CurlHttpResponse($httpStatusCode, $headers);
    }

    private function parseHeaders($rawHeaders)
    {
        $headers = [];
        $lines = explode("\r\n", $rawHeaders);
        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(': ', $line, 2);
                $headers[$key] = $value;
            }
        }
        return $headers;
    }
}

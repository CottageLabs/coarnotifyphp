# Creating Your Own HTTP Layer

The HTTP layer is the mechanism by which notifications are sent to the target inbox. The HTTP layer is designed to be 
customizable, so that you can use your own HTTP library or build in custom authentication routines.

The HTTP layer interface and default implementation can be found in `src/http`.

To use a custom HTTP layer, you provide this at the time of creating the client:

```php
use coarnotify\client\COARNotifyClient;
use my_custom_http_layer\MyCustomHTTPLayer;

$client = new COARNotifyClient(http_layer: new MyCustomHTTPLayer());
```

## Implementing HTTP Layer with Authentication

To add authentication to your HTTP layer, one way of doing this would be to extend the existing CURL-based 
implementation to layer the authentication on top.

```php
class AuthCurlHttpLayer extends CurlHttpLayer
{
    private string $username;
    private string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function post(string $url, string $data, ?array $headers = [], ...$args): CurlHttpResponse
    {
        $authHeader = $this->getAuthHeader();
        $headers = array_merge($headers, $authHeader);

        return parent::post($url, $data, $headers, ...$args);
    }

    public function get(string $url, ?array $headers = [], ...$args): CurlHttpResponse
    {
        $authHeader = $this->getAuthHeader();
        $headers = array_merge($headers, $authHeader);

        return parent::get($url, $headers, ...$args);
    }

    private function getAuthHeader(): array
    {
        $authToken = base64_encode("{$this->username}:{$this->password}");
        return ['Authorization: Basic ' . $authToken];
    }
}
```

## Implementing HTTP Layer with Alternative Library

If your application already relies on another HTTP library, you can implement the HTTP layer using that library instead.

```php
<?php

namespace my_custom_http_layer;

use coarnotify\http\HttpLayer;
use coarnotify\http\HttpResponse;
use my_http_library\MyHttpLibrary;

class MyCustomHTTPLayer implements HttpLayer
{
    public function post(string $url, string $data, array $headers = [], ...$args): CustomHttpResponse
    {
        $resp = MyHttpLibrary::makeRequest("POST", $url, $data, $headers, ...$args);
        return new CustomHttpResponse($resp);
    }

    public function get(string $url, array $headers = [], ...$args): CustomHttpResponse
    {
        $resp = MyHttpLibrary::makeRequest("GET", $url, null, $headers, ...$args);
        return new CustomHttpResponse($resp);
    }
}

class CustomHttpResponse implements HttpResponse
{
    private $resp;

    public function __construct($resp)
    {
        $this->resp = $resp;
    }

    public function header(string $headerName): string
    {
        return $this->resp->getResponseHeader($headerName);
    }

    public function getStatusCode(): int
    {
        return $this->resp->getStatusCode();
    }
}
```
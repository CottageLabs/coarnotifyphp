# Implementing a Server

To implement your own server-side inbox to receive and process COAR Notify notifications, this library provides some 
framework-agnostic supporting classes to help you get started.

The architecture this supports for implementations is as follows:

Your web framework provides the routes and the handler for incoming requests. The raw notification body can then be 
passed to the `coarnotify\server\COARNotifyServer` class to handle parsing and validation of the notification. 
Once this is done, it is passed on to your implementation of the `coarnotify\server\COARNotifyServiceBinding` class. 
This carries out the actions required for the notification and then responds with a 
`coarnotify\server\COARNotifyReceipt` object, which makes its way back to your web framework to be returned to the 
client in whatever way is most appropriate.

## Example Implementation

Built into this library is a test server which demonstrates a simple implementation of a server.

This example uses the Slim web framework and provides an `inbox` route as the target for notifications.

```php
$app->post('/inbox', function (Request $request, Response $response) use ($app) {
    // Retrieve and process a notification
}
```

### Custom Service Binding Class

To process a notification, you need to implement a custom service binding class that extends 
`coarnotify\server\COARNotifyServiceBinding`. This class receives the notification and processes it.

The notification received by the service binding is a full COAR Notify model object.

This example implementation receives the notification and writes it to a file in a configured directory. It then 
returns a location and a `CREATED` status.

```php
namespace coarnotify\server;

use coarnotify\core\notify\NotifyPattern;
use coarnotify\server\COARNotifyReceipt;

class COARNotifyServiceImpl extends COARNotifyServiceBinding
{
    public function notificationReceived(NotifyPattern $notification): COARNotifyReceipt
    {
        $store = config('app.store_dir');
        $now = (new \DateTime())->format('Ymd_His');
        $filename = $now . '_' . bin2hex(random_bytes(16)) . '.json';

        file_put_contents("$store/$filename", json_encode($notification->toJsonLd()));

        $location = url("/inbox/$filename");
        return new COARNotifyReceipt(COARNotifyReceipt::CREATED, $location);
    }
}
```

### Passing the Service Binding to the Server

You can now pass the custom service binding class to the COAR Notify server class on construction.

```php
use coarnotify\server\COARNotifyServer;

$server = new COARNotifyServer(new COARNotifyServiceImpl());
```

### Extending the `inbox` Route

Finally, extend the `inbox` route to use the `COARNotifyServer::receive` method to process the notification and 
handle the response to the user.

```php
$app->post('/inbox', function (Request $request, Response $response) use ($app) {
    $notification = $request->getParsedBody();
    $server = new COARNotifyServer(new COARNotifyServiceTestImpl());

    try {
        $result = $server->receive($notification, $app->getContainer()->get('settings')['validate_incoming']);
    } catch (COARNotifyServerError $e) {
        return $response->withStatus($e->getStatus())->write($e->getMessage());
    }

    $response = $response->withStatus($result->getStatus());
    if ($result->getStatus() == COARNotifyReceipt::CREATED) {
        $response = $response->withHeader('Location', $result->getLocation());
    }
    return $response;
});
```

Using this approach, the web layer is responsible only for reading the incoming request and returning a suitable 
response to the user. The COAR server handles the business of parsing and validating the content and then passes the 
request on to a web-independent controller you have supplied to process the notification.
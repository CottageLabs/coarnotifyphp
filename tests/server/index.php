<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use coarnotify\server\COARNotifyServer;
use Tests\server\COARNotifyServiceTestImpl;
use coarnotify\exceptions\COARNotifyServerError;
use coarnotify\server\COARNotifyReceipt;

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/local.php';
require __DIR__ . '/COARNotifyServiceTestImpl.php';
require __DIR__ . '/../../src/server/COARNotifyServer.php';
require __DIR__ . '/../../src/server/COARNotifyServerError.php';

function patchSettings($app) {
    $settings = $app->getContainer()->get('settings');
    $overrides = $app->getContainer()->get('overrides');
    foreach ($overrides as $key => $value) {
        $settings[$key] = $value;
    }
}

$app = AppFactory::create();

// $app->setBasePath('/path/to/your/app');

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

$app->run();
<?php

namespace coarnotify\server;

use coarnotify\factory\COARNotifyFactory;
use coarnotify\exceptions\COARNotifyServerError;
use coarnotify\core\notify\NotifyPattern;

/**
 * The main entrypoint to the COAR Notify server implementation.
 *
 * The web layer of your application should pass the json/raw payload of any incoming notification to the
 * `receive` method, which will parse the payload and pass it to the `COARNotifyServiceBinding.notificationReceived`
 * method of your service implementation
 *
 * This object should be constructed with your service implementation passed to it, for example
 *
 * ```php
 * $server = COARNotifyServer(MyServiceBinding())
 * try {
 *      $response = server->receive(the_request_json)
 *      return make_my_http_response($response);
 * } catch (COARNotifyServerError $e) {
 *      return my_http_error($e);
 * }
 *
 */
class COARNotifyServer
{
    /**
     * @var COARNotifyServiceBinding The service implementation
     */
    private $serviceImpl;

    /**
     * Construct a new COARNotifyServer with the given service implementation
     *
     * @param COARNotifyServiceBinding $serviceImpl Your service implementation
     */
    public function __construct(COARNotifyServiceBinding $serviceImpl)
    {
        $this->serviceImpl = $serviceImpl;
    }

    /**
     * Receive an incoming notification as JSON, parse and validate (optional) and then pass to the
     * service implementation
     *
     * @param array|string $raw The JSON representation of the data, either as a string or an array
     * @param bool $validate Whether to validate the notification before passing to the service implementation
     * @return COARNotifyReceipt The COARNotifyReceipt response from the service implementation
     * @throws COARNotifyServerError If the notification is invalid
     */
    public function receive($raw, bool $validate = true): COARNotifyReceipt
    {
        if (is_string($raw)) {
            $raw = json_decode($raw, true);
        }

        $obj = COARNotifyFactory::getByObject($raw);
        if ($validate && !$obj->validate()) {
            throw new COARNotifyServerError(400, "Invalid notification");
        }

        return $this->serviceImpl->notificationReceived($obj);
    }
}
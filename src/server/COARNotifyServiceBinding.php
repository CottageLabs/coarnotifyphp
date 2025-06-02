<?php

namespace coarnotify\server;

use coarnotify\core\notify\NotifyPattern;

/**
 * Interface for implementing a COAR Notify server binding.
 *
 * Server implementation should extend this class and implement the `notificationReceived` method
 *
 * That method will receive a `NotifyPattern` object, which will be one of the known types
 * and should return a `COARNotifyReceipt` object with the appropriate status code and location URL
 */
interface COARNotifyServiceBinding
{
    /**
     * Process the receipt of the given notification, and respond with an appropriate receipt object.
     *
     * @param NotifyPattern $notification The notification object received
     * @return COARNotifyReceipt The receipt object to send back to the client
     */
    public function notificationReceived(NotifyPattern $notification): COARNotifyReceipt;
}
<?php

namespace coarnotify\server;

use coarnotify\core\notify\NotifyPattern;

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
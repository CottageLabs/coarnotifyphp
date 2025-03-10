<?php

namespace Tests\server;

use coarnotify\core\notify\NotifyPattern;
use coarnotify\exceptions\COARNotifyServerError;
use coarnotify\server\COARNotifyReceipt;
use coarnotify\server\COARNotifyServiceBinding;

class COARNotifyServiceTestImpl implements COARNotifyServiceBinding
{
    public function notificationReceived(NotifyPattern $notification): COARNotifyReceipt
    {
        $store = __DIR__ . '/../store';
        if (!is_dir($store)) {
            throw new COARNotifyServerError(500, "Store directory does not exist");
        }

        $now = date("Ymd_His");
        $fn = $now . "_" . uniqid();

        file_put_contents("$store/$fn.json", json_encode($notification->toJsonLd()));

        $rstatus = 201;
        $location = "http://localhost:8080/inbox/$fn";

        return new COARNotifyReceipt($rstatus, $location);
    }
}
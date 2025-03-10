<?php

namespace Tests\integration;

use coarnotify\client\NotifyResponse;
use PHPUnit\Framework\TestCase;

use coarnotify\client\COARNotifyClient;
use coarnotify\patterns\accept\Accept;

use Tests\fixtures\AcceptFixtureFactory;

const INBOX = 'http://localhost:8080/inbox';

class TestClient extends TestCase
{
    public function test_01_accept()
    {
        $client = new COARNotifyClient(INBOX);
        $source = AcceptFixtureFactory::source();
        $acc = new Accept($source);
        $resp = $client->send($acc);
        $this->assertEquals($resp->getAction(), NotifyResponse::CREATED);
        $this->assertNotNull($resp->getLocation());
        echo $resp->getLocation();
    }
}
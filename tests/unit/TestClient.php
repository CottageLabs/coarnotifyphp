<?php

namespace Tests\unit;

use coarnotify\client\NotifyResponse;
use coarnotify\core\notify\NotifyPattern;
use PHPUnit\Framework\TestCase;

use coarnotify\client\COARNotifyClient;
//use coarnotify\patterns\AnnounceEndorsement;
use Tests\fixtures\AnnounceEndorsementFixtureFactory;
use Tests\fixtures\NotifyFixtureFactory;
use Tests\mocks\MockHttpResponse;
use Tests\mocks\MockHttpLayer;

class TestClient extends TestCase
{
    public function test_01_construction()
    {
        $client = new COARNotifyClient();
        $this->assertNull($client->getInboxUrl());

        $client = new COARNotifyClient("http://example.com/inbox");
        $this->assertEquals("http://example.com/inbox", $client->getInboxUrl());

        $client = new COARNotifyClient(null, new MockHttpLayer());
        $client = new COARNotifyClient("http://example.com/inbox", new MockHttpLayer());
    }

    public function test_02_created_response()
    {
        $client = new COARNotifyClient("http://example.com/inbox", new MockHttpLayer(
            201,
            "http://example.com/location"
        ));
        $source = NotifyFixtureFactory::source();
        $obj = new NotifyPattern($source);
        $resp = $client->send($obj);
        $this->assertEquals(NotifyResponse::CREATED, $resp->getAction());
        $this->assertEquals("http://example.com/location", $resp->getLocation());
    }

    public function test_03_accepted_response()
    {
        $client = new COARNotifyClient("http://example.com/inbox", new MockHttpLayer(
            202
        ));
        $source = NotifyFixtureFactory::source();
        $obj = new NotifyPattern($source);
        $resp = $client->send($obj);
        $this->assertEquals(NotifyResponse::ACCEPTED, $resp->getAction());
        $this->assertNull($resp->getLocation());
    }
}

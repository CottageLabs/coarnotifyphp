<?php

namespace Tests\integration;

use coarnotify\client\NotifyResponse;
use PHPUnit\Framework\TestCase;

use coarnotify\client\COARNotifyClient;
use coarnotify\patterns\accept\Accept;
use coarnotify\patterns\announce_endorsement\AnnounceEndorsement;
use coarnotify\patterns\announce_relationship\AnnounceRelationship;
use coarnotify\patterns\announce_review\AnnounceReview;
use coarnotify\patterns\announce_service_result\AnnounceServiceResult;
use coarnotify\patterns\reject\Reject;
use coarnotify\patterns\request_endorsement\RequestEndorsement;
use coarnotify\patterns\request_review\RequestReview;
use coarnotify\patterns\tentatively_accept\TentativelyAccept;
use coarnotify\patterns\tentatively_reject\TentativelyReject;
use coarnotify\patterns\unprocessable_notification\UnprocessableNotification;
use coarnotify\patterns\undo_offer\UndoOffer;

use Tests\fixtures\AcceptFixtureFactory;
use Tests\fixtures\AnnounceEndorsementFixtureFactory;
use Tests\fixtures\AnnounceRelationshipFixtureFactory;
use Tests\fixtures\AnnounceReviewFixtureFactory;
use Tests\fixtures\AnnounceServiceResultFixtureFactory;
use Tests\fixtures\RejectFixtureFactory;
use Tests\fixtures\RequestEndorsementFixtureFactory;
use Tests\fixtures\RequestReviewFixtureFactory;
use Tests\fixtures\TentativelyAcceptFixtureFactory;
use Tests\fixtures\TentativelyRejectFixtureFactory;
use Tests\fixtures\UnprocessableNotificationFixtureFactory;
use Tests\fixtures\UndoOfferFixtureFactory;

const INBOX = 'http://localhost:5005/inbox';

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

    public function test_02_announce_endorsement()
    {
        $client = new COARNotifyClient(INBOX);
        $source = AnnounceEndorsementFixtureFactory::source();
        $ae = new AnnounceEndorsement($source);
        $resp = $client->send($ae);
        $this->assertEquals($resp->getAction(), $resp::CREATED);
        $this->assertNotNull($resp->getLocation());
        echo $resp->getLocation();
    }

    public function test_04_announce_relationship()
    {
        $client = new COARNotifyClient(INBOX);
        $source = AnnounceRelationshipFixtureFactory::source();
        $ae = new AnnounceRelationship($source);
        $resp = $client->send($ae);
        $this->assertEquals($resp->getAction(), $resp::CREATED);
        $this->assertNotNull($resp->getLocation());
        echo $resp->getLocation();
    }

    public function test_05_announce_review()
    {
        $client = new COARNotifyClient(INBOX);
        $source = AnnounceReviewFixtureFactory::source();
        $ae = new AnnounceReview($source);
        $resp = $client->send($ae);
        $this->assertEquals($resp->getAction(), $resp::CREATED);
        $this->assertNotNull($resp->getLocation());
        echo $resp->getLocation();
    }

    public function test_06_announce_service_result()
    {
        $client = new COARNotifyClient(INBOX);
        $source = AnnounceServiceResultFixtureFactory::source();
        $ae = new AnnounceServiceResult($source);
        $resp = $client->send($ae);
        $this->assertEquals($resp->getAction(), $resp::CREATED);
        $this->assertNotNull($resp->getLocation());
        echo $resp->getLocation();
    }

    public function test_07_reject()
    {
        $client = new COARNotifyClient(INBOX);
        $source = RejectFixtureFactory::source();
        $ae = new Reject($source);
        $resp = $client->send($ae);
        $this->assertEquals($resp->getAction(), $resp::CREATED);
        $this->assertNotNull($resp->getLocation());
        echo $resp->getLocation();
    }

    public function test_08_request_endorsement()
    {
        $client = new COARNotifyClient(INBOX);
        $source = RequestEndorsementFixtureFactory::source();
        $ae = new RequestEndorsement($source);
        $resp = $client->send($ae);
        $this->assertEquals($resp->getAction(), $resp::CREATED);
        $this->assertNotNull($resp->getLocation());
        echo $resp->getLocation();
    }

    public function test_09_request_review()
    {
        $client = new COARNotifyClient(INBOX);
        $source = RequestReviewFixtureFactory::source();
        $ae = new RequestReview($source);
        $resp = $client->send($ae);
        $this->assertEquals($resp->getAction(), $resp::CREATED);
        $this->assertNotNull($resp->getLocation());
        echo $resp->getLocation();
    }

    public function test_10_tentatively_accept()
    {
        $client = new COARNotifyClient(INBOX);
        $source = TentativelyAcceptFixtureFactory::source();
        $ae = new TentativelyAccept($source);
        $resp = $client->send($ae);
        $this->assertEquals($resp->getAction(), $resp::CREATED);
        $this->assertNotNull($resp->getLocation());
        echo $resp->getLocation();
    }

    public function test_11_tentatively_reject()
    {
        $client = new COARNotifyClient(INBOX);
        $source = TentativelyRejectFixtureFactory::source();
        $ae = new TentativelyReject($source);
        $resp = $client->send($ae);
        $this->assertEquals($resp->getAction(), $resp::CREATED);
        $this->assertNotNull($resp->getLocation());
        echo $resp->getLocation();
    }

    public function test_12_unprocessable_notification()
    {
        $client = new COARNotifyClient(INBOX);
        $source = UnprocessableNotificationFixtureFactory::source();
        $ae = new UnprocessableNotification($source);
        $resp = $client->send($ae);
        $this->assertEquals($resp->getAction(), $resp::CREATED);
        $this->assertNotNull($resp->getLocation());
        echo $resp->getLocation();
    }

    public function test_13_undo_offer()
    {
        $client = new COARNotifyClient(INBOX);
        $source = UndoOfferFixtureFactory::source();
        $ae = new UndoOffer($source);
        $resp = $client->send($ae);
        $this->assertEquals($resp->getAction(), $resp::CREATED);
        $this->assertNotNull($resp->getLocation());
        echo $resp->getLocation();
    }
}
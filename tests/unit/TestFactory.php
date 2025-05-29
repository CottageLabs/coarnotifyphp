<?php

namespace Tests\unit;

use PHPUnit\Framework\TestCase;
use coarnotify\core\notify\NotifyPattern;
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
use coarnotify\patterns\undo_offer\UndoOffer;
use coarnotify\patterns\unprocessable_notification\UnprocessableNotification;

use coarnotify\factory\COARNotifyFactory;
use Tests\fixtures\{
    AcceptFixtureFactory,
    AnnounceEndorsementFixtureFactory,
    AnnounceRelationshipFixtureFactory,
    AnnounceReviewFixtureFactory,
    AnnounceServiceResultFixtureFactory,
    RejectFixtureFactory,
    RequestEndorsementFixtureFactory,
    RequestReviewFixtureFactory,
    TentativelyAcceptFixtureFactory,
    TentativelyRejectFixtureFactory,
    UnprocessableNotificationFixtureFactory,
    UndoOfferFixtureFactory
};
use Tests\mocks\MockNotifyPattern;

class TestFactory extends TestCase
{
    public function test_01_accept()
    {
        $acc = COARNotifyFactory::getByTypes(Accept::TYPE);
        $this->assertEquals($acc, Accept::class);

        $source = AcceptFixtureFactory::source();
        $acc = COARNotifyFactory::getByObject($source);
        $this->assertInstanceOf(Accept::class, $acc);

        $this->assertEquals($acc->getId(), $source['id']);
    }

    public function test_02_announce_endorsement()
    {
        $ae = COARNotifyFactory::getByTypes(AnnounceEndorsement::TYPE);
        $this->assertEquals($ae, AnnounceEndorsement::class);

        $source = AnnounceEndorsementFixtureFactory::source();
        $ae = COARNotifyFactory::getByObject($source);
        $this->assertInstanceOf(AnnounceEndorsement::class, $ae);

        $this->assertEquals($ae->getId(), $source['id']);
    }

    public function test_04_announce_relationship()
    {
        $ar = COARNotifyFactory::getByTypes(AnnounceRelationship::TYPE);
        $this->assertEquals($ar, AnnounceRelationship::class);

        $source = AnnounceRelationshipFixtureFactory::source();
        $ar = COARNotifyFactory::getByObject($source);
        $this->assertInstanceOf(AnnounceRelationship::class, $ar);

        $this->assertEquals($ar->getId(), $source['id']);
    }

    public function test_05_announce_review()
    {
        $ar = COARNotifyFactory::getByTypes(AnnounceReview::TYPE);
        $this->assertEquals($ar, AnnounceReview::class);

        $source = AnnounceReviewFixtureFactory::source();
        $ar = COARNotifyFactory::getByObject($source);
        $this->assertInstanceOf(AnnounceReview::class, $ar);

        $this->assertEquals($ar->getId(), $source['id']);
    }

    public function test_06_announce_service_result()
    {
        $ar = COARNotifyFactory::getByTypes(AnnounceServiceResult::TYPE);
        $this->assertEquals($ar, AnnounceServiceResult::class);

        $source = AnnounceServiceResultFixtureFactory::source();
        $ar = COARNotifyFactory::getByObject($source);
        $this->assertInstanceOf(AnnounceServiceResult::class, $ar);

        $this->assertEquals($ar->getId(), $source['id']);
    }

    public function test_07_reject()
    {
        $ar = COARNotifyFactory::getByTypes(Reject::TYPE);
        $this->assertEquals($ar, Reject::class);

        $source = RejectFixtureFactory::source();
        $ar = COARNotifyFactory::getByObject($source);
        $this->assertInstanceOf(Reject::class, $ar);

        $this->assertEquals($ar->getId(), $source['id']);
    }

    public function test_08_request_endorsement()
    {
        $ar = COARNotifyFactory::getByTypes(RequestEndorsement::TYPE);
        $this->assertEquals($ar, RequestEndorsement::class);

        $source = RequestEndorsementFixtureFactory::source();
        $ar = COARNotifyFactory::getByObject($source);
        $this->assertInstanceOf(RequestEndorsement::class, $ar);

        $this->assertEquals($ar->getId(), $source['id']);
    }

    public function test_10_request_review()
    {
        $ar = COARNotifyFactory::getByTypes(RequestReview::TYPE);
        $this->assertEquals($ar, RequestReview::class);

        $source = RequestReviewFixtureFactory::source();
        $ar = COARNotifyFactory::getByObject($source);
        $this->assertInstanceOf(RequestReview::class, $ar);

        $this->assertEquals($ar->getId(), $source['id']);
    }

    public function test_11_tentatively_accept()
    {
        $ar = COARNotifyFactory::getByTypes(TentativelyAccept::TYPE);
        $this->assertEquals($ar, TentativelyAccept::class);

        $source = TentativelyAcceptFixtureFactory::source();
        $ar = COARNotifyFactory::getByObject($source);
        $this->assertInstanceOf(TentativelyAccept::class, $ar);

        $this->assertEquals($ar->getId(), $source['id']);
    }

    public function test_12_tentatively_reject()
    {
        $ar = COARNotifyFactory::getByTypes(TentativelyReject::TYPE);
        $this->assertEquals($ar, TentativelyReject::class);

        $source = TentativelyRejectFixtureFactory::source();
        $ar = COARNotifyFactory::getByObject($source);
        $this->assertInstanceOf(TentativelyReject::class, $ar);

        $this->assertEquals($ar->getId(), $source['id']);
    }

    public function test_13_unprocessable_notification()
    {
        $ar = COARNotifyFactory::getByTypes(UnprocessableNotification::TYPE);
        $this->assertEquals($ar, UnprocessableNotification::class);

        $source = UnprocessableNotificationFixtureFactory::source();
        $ar = COARNotifyFactory::getByObject($source);
        $this->assertInstanceOf(UnprocessableNotification::class, $ar);

        $this->assertEquals($ar->getId(), $source['id']);
    }

    public function test_14_undo_offer()
    {
        $ar = COARNotifyFactory::getByTypes(UndoOffer::TYPE);
        $this->assertEquals($ar, UndoOffer::class);

        $source = UndoOfferFixtureFactory::source();
        $ar = COARNotifyFactory::getByObject($source);
        $this->assertInstanceOf(UndoOffer::class, $ar);

        $this->assertEquals($ar->getId(), $source['id']);
    }

    public function test_15_register()
    {
        COARNotifyFactory::register(MockNotifyPattern::class);

        $tp = COARNotifyFactory::getByTypes(Accept::TYPE);
        $this->assertEquals($tp, MockNotifyPattern::class);
    }
}
?>
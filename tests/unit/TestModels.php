<?php

namespace Tests\unit;

use PHPUnit\Framework\TestCase;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\NotifyService;
use coarnotify\core\notify\NotifyObject;
use coarnotify\core\notify\NotifyActor;
use coarnotify\core\notify\NotifyItem;
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
use Tests\fixtures\{
    AcceptFixtureFactory,
    AnnounceEndorsementFixtureFactory,
    AnnounceRelationshipFixtureFactory,
    AnnounceReviewFixtureFactory,
    AnnounceServiceResultFixtureFactory,
    NotifyFixtureFactory,
    RejectFixtureFactory,
    RequestEndorsementFixtureFactory,
    RequestReviewFixtureFactory,
    TentativelyAcceptFixtureFactory,
    TentativelyRejectFixtureFactory,
    UnprocessableNotificationFixtureFactory,
    UndoOfferFixtureFactory
};

class TestModels extends TestCase
{
    private function getTestableProperties(array $source, array $propMap = null): array
    {
        $expand = function ($node, $path) use (&$expand) {
            $paths = [];
            foreach ($node as $k => $v) {
                if (is_array($v)) {
                    $paths = array_merge($paths, $expand($v, "$path.$k"));
                } else {
                    if (is_int($k)) {
                        $paths[] = $path;
                        break;  # if we have an integer key, assume that all the keys are integer
                    }
                    $paths[] = "$path.$k";
                }
            }
            return array_filter(array_map(function ($p) {
                return ltrim($p, '.');
            }, $paths), function ($p) {
                return strpos($p, '@context') === false;
            });
        };

        $objProperties = $expand($source, "");

        if ($propMap === null) {
            $propMap = [
                // "inReplyTo" => "in_reply_to",
                "context.ietf:cite-as" => "context.citeAs",
                "context.ietf:item.id" => "context.item.id",
                "context.ietf:item.mediaType" => "context.item.mediaType",
                "context.ietf:item.type" => "context.item.type",
                "object.as:subject" => "object.triple[2]",
                "object.as:relationship" => "object.triple[1]",
                "object.as:object" => "object.triple[0]",
                "object.ietf:cite-as" => "object.citeAs",
                "object.ietf:item.id" => "object.item.id",
                "object.ietf:item.mediaType" => "object.item.mediaType",
                "object.ietf:item.type" => "object.item.type",
                "object.object.ietf:cite-as" => "object.object.citeAs",
                "object.object.ietf:item.id" => "object.object.item.id",
                "object.object.ietf:item.mediaType" => "object.object.item.mediaType",
                "object.object.ietf:item.type" => "object.object.item.type",
            ];
        }

        return array_map(function ($p) use ($propMap) { return array_key_exists($p, $propMap) ? [$propMap[$p], $p] : $p;}, $objProperties);
    }

    private function applyPropertyTest(array $proptest, $obj, $fixtures)
    {
        $getProp = function ($source, $prop) {
            $p = is_array($prop) ? $prop[1] : $prop;
            $bits = explode('.', $p);
            foreach ($bits as $bit) {
                $idx = null;
                if (strpos($bit, '[') !== false) {
                    list($bit, $idx) = explode('[', $bit);
                    $idx = (int) rtrim($idx, ']');
                }
                $accessor = "get" . ucfirst($bit);
                $source = $source->$accessor();
                if ($idx !== null) {
                    $source = $source[$idx];
                }
            }
            return $source;
        };

        foreach ($proptest as $prop) {
            $oprop = is_array($prop) ? $prop[0] : $prop;
            $fprop = is_array($prop) ? $prop[1] : $prop;

            $oval = $getProp($obj, $oprop);
            $eval = $fixtures->expectedValue($fprop);

            if (is_array($oval) && count($oval) === 1) {
                $oval = $oval[0];
            }
            if (is_array($eval) && count($eval) === 1) {
                $eval = $eval[0];
            }

            $this->assertEquals($oval, $eval);
        }
    }

    public function testNotifyManualConstruct()
    {
        $n = new NotifyPattern();

        $this->assertNotNull($n->getId());
        $this->assertStringStartsWith("urn:uuid:", $n->getId());
        $this->assertEquals(NotifyPattern::TYPE, $n->getType());
        $this->assertNull($n->getOrigin());
        $this->assertNull($n->getTarget());
        $this->assertNull($n->getObject());
        $this->assertNull($n->getActor());
        $this->assertNull($n->getInReplyTo());
        $this->assertNull($n->getContext());

        $n->setId("urn:whatever");
        $n->ALLOWED_TYPES = ["Object", "Other"];
        $n->setType("Other");

        $origin = new NotifyService();
        $this->assertNotNull($origin->getId());
        $this->assertEquals($origin::DEFAULT_TYPE, $origin->getType());
        $origin->setInbox("http://origin.com/inbox");
        $n->setOrigin($origin);

        $target = new NotifyService();
        $target->setInbox("http://target.com/inbox");
        $n->setTarget($target);

        $obj = new NotifyObject();
        $this->assertNotNull($obj->getId());
        $this->assertNull($obj->getType());
        $n->setObject($obj);

        $actor = new NotifyActor();
        $this->assertNotNull($actor->getId());
        $this->assertEquals($actor::DEFAULT_TYPE, $actor->getType());
        $n->setActor($actor);

        $n->setInReplyTo("urn:irt");

        $context = new NotifyObject();
        $this->assertNotNull($context->getId());
        $this->assertNull($context->getType());
        $n->setContext($context);

        $this->assertEquals("urn:whatever", $n->getId());
        $this->assertEquals("Other", $n->getType());
        $this->assertEquals($origin->getId(), $n->getOrigin()->getId());
        $this->assertEquals($origin::DEFAULT_TYPE, $n->getOrigin()->getType());
        $this->assertEquals("http://origin.com/inbox", $n->getOrigin()->getInbox());
        $this->assertEquals($target->getId(), $n->getTarget()->getId());
        $this->assertEquals($target::DEFAULT_TYPE, $n->getTarget()->getType());
        $this->assertEquals("http://target.com/inbox", $n->getTarget()->getInbox());
        $this->assertEquals($obj->getId(), $n->getObject()->getId());
        $this->assertNull($n->getObject()->getType());
        $this->assertEquals($actor->getId(), $n->getActor()->getId());
        $this->assertEquals($actor::DEFAULT_TYPE, $n->getActor()->getType());
        $this->assertEquals("urn:irt", $n->getInReplyTo());
        $this->assertEquals($context->getId(), $n->getContext()->getId());
        $this->assertNull($n->getContext()->getType());
    }

    public function testNotifyFromFixture()
    {
        $source = NotifyFixtureFactory::source();
        $n = new NotifyPattern($source);

        $this->assertEquals($source['id'], $n->getId());
        $this->assertEquals($source['type'], $n->getType());
        $this->assertInstanceOf(NotifyService::class, $n->getOrigin());
        $this->assertEquals($source['origin']['id'], $n->getOrigin()->getId());
        $this->assertInstanceOf(NotifyObject::class, $n->getObject());
        $this->assertEquals($source['object']['id'], $n->getObject()->getId());
        $this->assertInstanceOf(NotifyService::class, $n->getTarget());
        $this->assertEquals($source['target']['id'], $n->getTarget()->getId());
        $this->assertInstanceOf(NotifyActor::class, $n->getActor());
        $this->assertEquals($source['actor']['id'], $n->getActor()->getId());
        $this->assertEquals($source['inReplyTo'], $n->getInReplyTo());
        $this->assertInstanceOf(NotifyObject::class, $n->getContext());
        $this->assertEquals($source['context']['id'], $n->getContext()->getId());
        $this->assertInstanceOf(NotifyItem::class, $n->getContext()->getItem());
        $this->assertEquals($source['context']['ietf:item']['id'], $n->getContext()->getItem()->getId());

        $n->setId("urn:whatever");
        $n->ALLOWED_TYPES = ["Object", "Other"];
        $n->setType("Other");
        $this->assertEquals("urn:whatever", $n->getId());
        $this->assertEquals("Other", $n->getType());
    }

    public function testNotifyOperations()
    {
        $n = new NotifyPattern();
        $this->expectException(ValidationError::class);
        $n->validate();
        $this->assertNotNull($n->toJsonLd());

        $source = NotifyFixtureFactory::source();
        $compare = $source;
        $n = new NotifyPattern($source);
        $this->assertTrue($n->validate());
        $this->assertEquals($compare, $n->toJsonLd());
    }

    public function testAccept()
    {
        $a = new Accept();

        $source = AcceptFixtureFactory::source();
        $compare = $source;
        $a = new Accept($source);
        $this->assertTrue($a->validate());
        $this->assertEquals($compare, $a->toJsonLd());

        $proptest = $this->getTestableProperties($compare);
        $this->applyPropertyTest($proptest, $a, new AcceptFixtureFactory());
    }

    public function testAnnounceEndorsement()
    {
        $ae = new AnnounceEndorsement();
        $source = AnnounceEndorsementFixtureFactory::source();
        $compare = $source;
        $ae = new AnnounceEndorsement($source);
        $this->assertTrue($ae->validate());
        $this->assertEquals($compare, $ae->toJsonLd());

        $proptest = $this->getTestableProperties($compare);
        $this->applyPropertyTest($proptest, $ae, new AnnounceEndorsementFixtureFactory());
    }

    public function testAnnounceRelationship()
    {
        $ae = new AnnounceRelationship();

        $source = AnnounceRelationshipFixtureFactory::source();
        $compare = $source;
        $ae = new AnnounceRelationship($source);
        $this->assertTrue($ae->validate());
        $this->assertEquals($compare, $ae->toJsonLd());

        $proptest = $this->getTestableProperties($compare);
        $this->applyPropertyTest($proptest, $ae, new AnnounceRelationshipFixtureFactory());
    }

    public function testAnnounceReview()
    {
        $ar = new AnnounceReview();

        $source = AnnounceReviewFixtureFactory::source();
        $compare = $source;
        $ar = new AnnounceReview($source);
        $this->assertTrue($ar->validate());
        $this->assertEquals($compare, $ar->toJsonLd());

        $proptest = $this->getTestableProperties($compare);
        $this->applyPropertyTest($proptest, $ar, new AnnounceReviewFixtureFactory());
    }

    public function testAnnounceServiceResult()
    {
        $asr = new AnnounceServiceResult();

        $source = AnnounceServiceResultFixtureFactory::source();
        $compare = $source;
        $compare['type'] = $compare['type'][0];
        $asr = new AnnounceServiceResult($source);

        $this->assertTrue($asr->validate());
        $this->assertEquals($compare, $asr->toJsonLd());

        $proptest = $this->getTestableProperties($compare);
        $this->applyPropertyTest($proptest, $asr, new AnnounceServiceResultFixtureFactory());
    }

    public function testReject()
    {
        $rej = new Reject();

        $source = RejectFixtureFactory::source();
        $compare = $source;
        $rej = new Reject($source);
        $this->assertTrue($rej->validate());
        $this->assertEquals($compare, $rej->toJsonLd());

        $proptest = $this->getTestableProperties($compare);
        $this->applyPropertyTest($proptest, $rej, new RejectFixtureFactory());
    }

    public function testRequestEndorsement()
    {
        $re = new RequestEndorsement();

        $source = RequestEndorsementFixtureFactory::source();
        $compare = $source;
        $re = new RequestEndorsement($source);

        $this->assertTrue($re->validate());
        $this->assertEquals($compare, $re->toJsonLd());

        $proptest = $this->getTestableProperties($compare);
        $this->applyPropertyTest($proptest, $re, new RequestEndorsementFixtureFactory());
    }

    public function testRequestReview()
    {
        $ri = new RequestReview();

        $source = RequestReviewFixtureFactory::source();
        $compare = $source;
        $ri = new RequestReview($source);

        $this->assertTrue($ri->validate());
        $this->assertEquals($compare, $ri->toJsonLd());

        $proptest = $this->getTestableProperties($compare);
        $this->applyPropertyTest($proptest, $ri, new RequestReviewFixtureFactory());
    }

    public function testTentativelyAccept()
    {
        $ta = new TentativelyAccept();

        $source = TentativelyAcceptFixtureFactory::source();
        $compare = $source;
        $ta = new TentativelyAccept($source);

        $this->assertTrue($ta->validate());
        $this->assertEquals($compare, $ta->toJsonLd());

        $proptest = $this->getTestableProperties($compare);
        $this->applyPropertyTest($proptest, $ta, new TentativelyAcceptFixtureFactory());
    }

    public function testTentativelyReject()
    {
        $ta = new TentativelyReject();

        $source = TentativelyRejectFixtureFactory::source();
        $compare = $source;
        $ta = new TentativelyReject($source);

        $this->assertTrue($ta->validate());
        $this->assertEquals($compare, $ta->toJsonLd());

        $proptest = $this->getTestableProperties($compare);
        $this->applyPropertyTest($proptest, $ta, new TentativelyRejectFixtureFactory());
    }

    public function testUnprocessableNotification()
    {
        $ta = new UnprocessableNotification();

        $source = UnprocessableNotificationFixtureFactory::source();
        $compare = $source;
        $ta = new UnprocessableNotification($source);

        $this->assertTrue($ta->validate());
        $this->assertEquals($compare, $ta->toJsonLd());

        $proptest = $this->getTestableProperties($compare);
        $this->applyPropertyTest($proptest, $ta, new UnprocessableNotificationFixtureFactory());
    }

    public function testUndoOffer()
    {
        $ta = new UndoOffer();

        $source = UndoOfferFixtureFactory::source();
        $compare = $source;
        $ta = new UndoOffer($source);

        $this->assertTrue($ta->validate());
        $this->assertEquals($compare, $ta->toJsonLd());

        $proptest = $this->getTestableProperties($compare);
        $this->applyPropertyTest($proptest, $ta, new UndoOfferFixtureFactory());
    }
}
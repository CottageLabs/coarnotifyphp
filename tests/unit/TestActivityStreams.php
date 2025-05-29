<?php

namespace Tests\unit;

use coarnotify\core\activitystreams2\ActivityStream;
use coarnotify\core\activitystreams2\Properties;
use Tests\fixtures\AnnounceEndorsementFixtureFactory;

use PHPUnit\Framework\TestCase;

class TestActivityStreams extends TestCase
{
    public function testConstruction()
    {
        $as2 = new ActivityStream();
        $this->assertEquals([], $as2->getDoc());
        $this->assertEquals([], $as2->getContext());

        $source = AnnounceEndorsementFixtureFactory::source();
        $s2 = $source;
        $s2context = $s2["@context"];
        unset($s2["@context"]);

        $as2 = new ActivityStream($source);

        $this->assertEquals($s2, $as2->getDoc());
        $this->assertEquals($s2context, $as2->getContext());
    }

    public function testSetProperties()
    {
        $as2 = new ActivityStream();

        // properties that are just basic json
        $as2->setProperty("random", "value");
        $this->assertEquals("value", $as2->getDoc()["random"]);
        $this->assertEquals([], $as2->getContext());

        // properties that are in the ASProperties
        $as2->setProperty(Properties::ID, "value");
        $this->assertEquals("value", $as2->getDoc()["id"]);
        $this->assertEquals([Properties::ID[2]], $as2->getContext());

        $as2->setProperty(Properties::TYPE, "another");
        $this->assertEquals("another", $as2->getDoc()["type"]);
        $this->assertEquals([Properties::ID[2]], $as2->getContext());

        // other variations on property namespaces
        $as2->setProperty(["as_object", "object", "http://example.com"], "object value");
        $as2->setProperty(["as_subject", "subject", "http://example.com"], "subject value");
        $this->assertEquals("object value", $as2->getDoc()["object"]);
        $this->assertEquals("subject value", $as2->getDoc()["subject"]);
        $this->assertEquals([Properties::ID[2], "http://example.com"], $as2->getContext());

        $as2->setProperty(["foaf_name", "foaf:name", ["foaf", "http://xmlns.com/foaf/0.1"]], "name value");
        $as2->setProperty(["foaf_email", "foaf:email", ["foaf", "http://xmlns.com/foaf/0.1"]], "email value");
        $this->assertEquals("name value", $as2->getDoc()["foaf:name"]);
        $this->assertEquals("email value", $as2->getDoc()["foaf:email"]);
        $this->assertEquals([Properties::ID[2], "http://example.com", ["foaf" => "http://xmlns.com/foaf/0.1"]], $as2->getContext());
    }

    public function testGetProperties()
    {
        $as2 = new ActivityStream();
        $as2->setProperty("random", "value");
        $as2->setProperty(Properties::ID, "id");
        $as2->setProperty(["as_object", "object", "http://example.com"], "object value");
        $as2->setProperty(["foaf_name", "foaf:name", ["foaf", "http://xmlns.com/foaf/0.1"]], "name value");

        $this->assertEquals("value", $as2->getProperty("random"));
        $this->assertEquals("id", $as2->getProperty(Properties::ID));
        $this->assertEquals("object value", $as2->getProperty(["as_object", "object", "http://example.com"]));
        $this->assertEquals("object value", $as2->getProperty("object"));
        $this->assertEquals("name value", $as2->getProperty(["foaf_name", "foaf:name", ["foaf", "http://xmlns.com/foaf/0.1"]]));
        $this->assertEquals("name value", $as2->getProperty("foaf:name"));
    }

    public function testToJsonLd()
    {
        // check we can round trip a document
        $source = AnnounceEndorsementFixtureFactory::source();
        $s2 = $source;
        $as2 = new ActivityStream($source);
        $this->assertEquals($s2, $as2->toJsonLd());

        // check we can build a document from scratch and get an expected result
        $as2 = new ActivityStream();
        $as2->setProperty("random", "value");
        $as2->setProperty(Properties::ID, "id");
        $as2->setProperty(["as_object", "object", "http://example.com"], "object value");
        $as2->setProperty(["foaf_name", "foaf:name", ["foaf", "http://xmlns.com/foaf/0.1"]], "name value");

        $expected = [
            "@context" => [Properties::ID[2], "http://example.com", ["foaf" => "http://xmlns.com/foaf/0.1"]],
            "random" => "value",
            "id" => "id",
            "object" => "object value",
            "foaf:name" => "name value"
        ];

        $this->assertEquals($expected, $as2->toJsonLd());
    }
}
?>

<?php

namespace Tests\unit;

use Tests\fixtures\NotifyFixtureFactory;
use Tests\fixtures\URIFixtureFactory;

use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\NotifyService;
use coarnotify\core\notify\NotifyObject;
use coarnotify\exceptions\ValidationError;
use coarnotify\exceptions\ValueError;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\validate\Validators;


use PHPUnit\Framework\TestCase;

class TestValidate extends TestCase
{
    public function test_01_structural_empty()
    {
        $n = new NotifyPattern();
        $n->setId(null); // these are automatically set, so remove them to trigger validation
        $n->setType(null);

        $ve = null;
        try {
            $n->validate();
        } catch (ValidationError $e) {
            $ve = $e;
        }
        $this->assertInstanceOf(ValidationError::class, $ve);

        $errors = $ve->getErrors();
        $this->assertArrayHasKey(Properties::canonicalName(Properties::ID), $errors);
        $this->assertArrayHasKey(Properties::canonicalName(Properties::TYPE), $errors);
        $this->assertArrayHasKey(Properties::canonicalName(Properties::OBJECT), $errors);
        $this->assertArrayHasKey(Properties::canonicalName(Properties::TARGET), $errors);
        $this->assertArrayHasKey(Properties::canonicalName(Properties::ORIGIN), $errors);
    }

    public function test_02_structural_basic()
    {
        $n = new NotifyPattern();
        $ve = null;
        try {
            $n->validate();
        } catch (ValidationError $e) {
            $ve = $e;
        }
        $this->assertInstanceOf(ValidationError::class, $ve);

        $errors = $ve->getErrors();
        $this->assertArrayNotHasKey(Properties::canonicalName(Properties::ID), $errors);
        $this->assertArrayNotHasKey(Properties::canonicalName(Properties::TYPE), $errors);
        $this->assertArrayHasKey(Properties::canonicalName(Properties::OBJECT), $errors);
        $this->assertArrayHasKey(Properties::canonicalName(Properties::TARGET), $errors);
        $this->assertArrayHasKey(Properties::canonicalName(Properties::ORIGIN), $errors);
    }

    public function test_03_structural_valid_document()
    {
        $n = new NotifyPattern();
        $n->setTarget(NotifyFixtureFactory::target());
        $n->setOrigin(NotifyFixtureFactory::origin());
        $n->setObject(NotifyFixtureFactory::object());

        $this->assertTrue($n->validate());
    }

    public function test_04_structural_invalid_nested()
    {
        $n = new NotifyPattern();
        $n->setTarget(new NotifyService(["whatever" => "value"], false));
        $n->setOrigin(new NotifyService(["another" => "junk"], false));
        $n->setObject(new NotifyObject(["yet" => "more"], false));

        $ve = null;
        try {
            $n->validate();
        } catch (ValidationError $e) {
            $ve = $e;
        }
        $this->assertInstanceOf(ValidationError::class, $ve);

        $errors = $ve->getErrors();
        $this->assertArrayNotHasKey(Properties::canonicalName(Properties::ID), $errors);
        $this->assertArrayNotHasKey(Properties::canonicalName(Properties::TYPE), $errors);
        $this->assertArrayNotHasKey(Properties::canonicalName(Properties::OBJECT), $errors);
        $this->assertArrayHasKey(Properties::canonicalName(Properties::TARGET), $errors);
        $this->assertArrayHasKey(Properties::canonicalName(Properties::ORIGIN), $errors);
    }

    public function test_05_validation_modes()
    {
        $valid = NotifyFixtureFactory::source();
        $n = new NotifyPattern($valid, true);

        $invalid = NotifyFixtureFactory::source();
        $invalid["id"] = "http://example.com/^path";
        $ve = null;
        try {
            $n = new NotifyPattern($invalid, true);
        } catch (ValidationError $e) {
            $ve = $e;
        }
        $this->assertInstanceOf(ValidationError::class, $ve);

        $this->assertNotNull($ve->getErrors()[Properties::canonicalName(Properties::ID)]);

        $valid = NotifyFixtureFactory::source();
        $n = new NotifyPattern($valid, false);

        $invalid = NotifyFixtureFactory::source();
        $invalid["id"] = "http://example.com/^path";
        $n = new NotifyPattern($invalid, false);

        $n = new NotifyPattern(validate_properties: false);
        $n->setId("urn:uuid:4fb3af44-d4f8-4226-9475-2d09c2d8d9e0"); // valid
        $n->setId("http://example.com/^path"); // invalid

        $ve = null;
        try {
            $n->validate();
        } catch (ValidationError $e) {
            $ve = $e;
        }
        $this->assertInstanceOf(ValidationError::class, $ve);

        $this->assertNotNull($ve->getErrors()[Properties::canonicalName(Properties::ID)]);
    }

    public function test_06_validate_id_property()
    {
        $n = new NotifyPattern();
        // test the various ways it can fail:

        $ve = null;
        try {
            $n->setId("9whatever:none");
        } catch (ValueError $e) {
            $ve = $e;
        }
        $this->assertInstanceOf(ValueError::class, $ve);
        $this->assertEquals("Invalid URI scheme `9whatever`", $ve->getMessage());

        $ve = null;
        try {
            $n->setId("http://wibble/stuff");
        } catch (ValueError $e) {
            $ve = $e;
        }
        $this->assertInstanceOf(ValueError::class, $ve);
        $this->assertEquals("Invalid URI authority `wibble`", $ve->getMessage());

        $ve = null;
        try {
            $n->setId("http://example.com/^path");
        } catch (ValueError $e) {
            $ve = $e;
        }
        $this->assertInstanceOf(ValueError::class, $ve);
        $this->assertEquals("Invalid URI path `/^path`", $ve->getMessage());

        $ve = null;
        try {
            $n->setId("http://example.com/path/here/?^=what");
        } catch (ValueError $e) {
            $ve = $e;
        }
        $this->assertInstanceOf(ValueError::class, $ve);
        $this->assertEquals("Invalid URI query `^=what`", $ve->getMessage());

        $ve = null;
        try {
            $n->setId("http://example.com/path/here/?you=what#^frag");
        } catch (ValueError $e) {
            $ve = $e;
        }
        $this->assertInstanceOf(ValueError::class, $ve);
        $this->assertEquals("Invalid URI fragment `^frag`", $ve->getMessage());

        // test a bunch of successful ones

        // These ones taken from wikipedia
        $n->setId("https://john.doe@www.example.com:1234/forum/questions/?tag=networking&order=newest#top");
        $n->setId("https://john.doe@www.example.com:1234/forum/questions/?tag=networking&order=newest#:~:text=whatever");
        $n->setId("ldap://[2001:db8::7]/c=GB?objectClass?one");
        $n->setId("mailto:John.Doe@example.com");
        $n->setId("news:comp.infosystems.www.servers.unix");
        $n->setId("tel:+1-816-555-1212");
        $n->setId("telnet://192.0.2.16:80/");
        $n->setId("urn:oasis:names:specification:docbook:dtd:xml:4.1.2");

        // these ones taken from the spec
        $n->setId("urn:uuid:4fb3af44-d4f8-4226-9475-2d09c2d8d9e0");
        $n->setId("https://generic-service.com/system");
        $n->setId("https://generic-service.com/system/inbox/");
    }

    public function test_07_validate_url()
    {
        $urls = URIFixtureFactory::generate(["http", "https"]);
        // print($urls);

        foreach ($urls as $url) {
            // print($url);
            $this->assertTrue(Validators::url(null, $url));
        }

        $this->expectException(ValueError::class);
        Validators::url(null, "ftp://example.com");
        $this->expectException(ValueError::class);
        Validators::url(null, "http:/example.com");
        $this->expectException(ValueError::class);
        Validators::url(null, "http://domain/path");
        $this->expectException(ValueError::class);
        Validators::url(null, "http://example.com/path^wrong");
    }

    public function test_08_one_of()
    {
        $values = ["a", "b", "c"];
        $validator = Validators::oneOf($values);
        $this->assertTrue($validator(null, "a"));
        $this->assertTrue($validator(null, "b"));
        $this->assertTrue($validator(null, "c"));

        $this->expectException(ValueError::class);
        $validator(null, "d");

        $this->expectException(ValueError::class);
        // one_of expects a singular value, it does not do lists
        $validator(null, ["a", "b"]);
    }

    public function test_09_contains()
    {
        $validator = Validators::contains("a");
        $this->assertTrue($validator(null, ["a", "b", "c"]));

        $this->expectException(ValueError::class);
        $validator(null, ["b", "c", "d"]);
    }

    public function test_10_at_least_one_of()
    {
        $values = ["a", "b", "c"];
        $validator = Validators::atLeastOneOf($values);
        $this->assertTrue($validator(null, "a"));
        $this->assertTrue($validator(null, "b"));
        $this->assertTrue($validator(null, "c"));

        $this->expectException(ValueError::class);
        $validator(null, "d");

        // at_least_one_of can take a list and validate each one against the global criteria
        $this->assertTrue($validator(null, ["a", "d"]));
    }
}
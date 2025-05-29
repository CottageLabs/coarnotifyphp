<?php

namespace Tests\fixtures;

abstract class BaseFixtureFactory
{
    public static function source(bool $copy = true)
    {
        throw new \Exception("NotImplementedError");
    }

    public static function invalid()
    {
        $source = static::source();
        static::baseInvalid($source);
        return $source;
    }

    public static function expectedValue($path)
    {
        $source = static::source(false); // we're only reading the value, so no need to clone it
        return static::valueFromDict($path, $source);
    }

    protected static function baseInvalid(&$source)
    {
        $source["id"] = "not a uri";
        $source["inReplyTo"] = "not a uri";
        $source["origin"]["id"] = "urn:uuid:4fb3af44-d4f8-4226-9475-2d09c2d8d9e0";
        $source["origin"]["inbox"] = "not a uri";
        $source["origin"]["type"] = "NotAValidType";
        $source["target"]["id"] = "urn:uuid:4fb3af44-d4f8-4226-9475-2d09c2d8d9e0";
        $source["target"]["inbox"] = "not a uri";
        $source["target"]["type"] = "NotAValidType";
        $source["type"] = "NotAValidType";
        return $source;
    }

    protected static function actorInvalid(&$source)
    {
        $source["actor"]["id"] = "not a uri";
        $source["actor"]["type"] = "NotAValidType";
        return $source;
    }

    protected static function objectInvalid(&$source)
    {
        $source["object"]["id"] = "not a uri";
        $source["object"]["cite_as"] = "urn:uuid:4fb3af44-d4f8-4226-9475-2d09c2d8d9e0";
        return $source;
    }

    protected static function contextInvalid(&$source)
    {
        $source["context"]["id"] = "not a uri";
        $source["context"]["type"] = "NotAValidType";
        $source["context"]["cite_as"] = "urn:uuid:4fb3af44-d4f8-4226-9475-2d09c2d8d9e0";
        return $source;
    }

    protected static function valueFromDict($path, $dictionary)
    {
        $bits = explode(".", $path);
        $node = $dictionary;
        foreach ($bits as $bit) {
            $node = $node[$bit];
        }
        return $node;
    }

    protected static function deepCopy($array)
    {
        return json_decode(json_encode($array), true);
    }
}
?>
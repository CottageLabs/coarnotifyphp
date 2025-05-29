<?php

namespace Tests\fixtures;

use Tests\fixtures\BaseFixtureFactory;

class AnnounceServiceResultFixtureFactory extends BaseFixtureFactory
{
    public static function source(bool $copy = true)
    {
        if ($copy) {
            return self::deepCopy(self::ANNOUNCE_SERVICE_RESULT);
        }
        return self::ANNOUNCE_SERVICE_RESULT;
    }

    public static function invalid()
    {
        $source = self::source();
        self::baseInvalid($source);
        self::actorInvalid($source);
        self::objectInvalid($source);
        self::contextInvalid($source);
        return $source;
    }

    private const ANNOUNCE_SERVICE_RESULT = [
        "@context" => [
            "https://www.w3.org/ns/activitystreams",
            "https://coar-notify.net"
        ],
        "actor" => [
            "id" => "https://overlay-journal.com",
            "name" => "Overlay Journal",
            "type" => "Service"
        ],
        "context" => [
            "id" => "https://research-organisation.org/repository/preprint/201203/421/"
        ],
        "id" => "urn:uuid:94ecae35-dcfd-4182-8550-22c7164fe23f",
        "inReplyTo" => "urn:uuid:0370c0fb-bb78-4a9b-87f5-bed307a509dd",
        "object" => [
            "id" => "https://overlay-journal.com/information-page",
            "type" => [
                "Page",
                "sorg:WebPage"
            ]
        ],
        "origin" => [
            "id" => "https://overlay-journal.com/system",
            "inbox" => "https://overlay-journal.com/inbox/",
            "type" => "Service"
        ],
        "target" => [
            "id" => "https://generic-service.com/system",
            "inbox" => "https://generic-service.com/system/inbox/",
            "type" => "Service"
        ],
        "type" => [
            "Announce"
        ]
    ];
}
<?php

namespace Tests\fixtures;

use coarnotify\core\notify\NotifyObject;
use coarnotify\core\notify\NotifyService;

class NotifyFixtureFactory
{
    public static function source()
    {
        return self::deepCopy(self::BASE_NOTIFY);
    }

    public static function target()
    {
        return new NotifyService(self::deepCopy(self::BASE_NOTIFY['target']));
    }

    public static function origin()
    {
        return new NotifyService(self::deepCopy(self::BASE_NOTIFY['origin']));
    }

    public static function object()
    {
        return new NotifyObject(self::deepCopy(self::BASE_NOTIFY['object']));
    }

    private static function deepCopy($array)
    {
        return json_decode(json_encode($array), true);
    }

    private const BASE_NOTIFY = [
        "@context" => [
            "https://www.w3.org/ns/activitystreams",
            "https://purl.org/coar/notify"
        ],
        "id" => "urn:uuid:94ecae35-dcfd-4182-8550-22c7164fe23f",
        "type" => "Object",
        "origin" => [
            "id" => "https://overlay-journal.com/system",
            "inbox" => "https://overlay-journal.com/inbox/",
            "type" => "Service"
        ],
        "object" => [
            "id" => "https://overlay-journal.com/articles/00001/",
            "ietf:cite-as" => "https://overlay-journal.com/articles/00001/",
            "type" => [
                "Page",
                "sorg:WebPage"
            ]
        ],
        "target" => [
            "id" => "https://research-organisation.org/repository",
            "inbox" => "https://research-organisation.org/inbox/",
            "type" => "Service"
        ],
        "actor" => [
            "id" => "https://overlay-journal.com",
            "name" => "Overlay Journal",
            "type" => "Service"
        ],
        "inReplyTo" => "urn:uuid:0370c0fb-bb78-4a9b-87f5-bed307a509dd",
        "context" => [
            "id" => "https://research-organisation.org/repository/preprint/201203/421/",
            "ietf:cite-as" => "https://doi.org/10.5555/12345680",
            "ietf:item" => [
                "id" => "https://research-organisation.org/repository/preprint/201203/421/content.pdf",
                "mediaType" => "application/pdf",
                "type" => [
                    "Article",
                    "sorg:ScholarlyArticle"
                ]
            ],
            "type" => "sorg:AboutPage"
        ]
    ];
}

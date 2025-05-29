<?php

namespace Tests\fixtures;

use Tests\fixtures\BaseFixtureFactory;

class UnprocessableNotificationFixtureFactory extends BaseFixtureFactory
{
    public static function source(bool $copy = true)
    {
        if ($copy) {
            return self::deepCopy(self::UNPROCESSABLE_NOTIFICATION);
        }
        return self::UNPROCESSABLE_NOTIFICATION;
    }

    public static function invalid()
    {
        $source = self::source();
        self::baseInvalid($source);
        unset($source['summary']);
        return $source;
    }

    private const UNPROCESSABLE_NOTIFICATION = [
        "@context" => [
            "https://www.w3.org/ns/activitystreams",
            "https://coar-notify.net"
        ],
        "actor" => [
            "id" => "https://generic-service-1.com",
            "name" => "Generic Service",
            "type" => "Service"
        ],
        "id" => "urn:uuid:49dae4d9-4a16-4dcf-8ae0-a0cef139254c",
        "inReplyTo" => "urn:uuid:0370c0fb-bb78-4a9b-87f5-bed307a509dd",
        "object" => [
            "id" => "urn:uuid:0370c0fb-bb78-4a9b-87f5-bed307a509dd"
        ],
        "origin" => [
            "id" => "https://some-organisation.org",
            "inbox" => "https://some-organisation.org/inbox/",
            "type" => "Service"
        ],
        "summary" => "Unable to process URL: http://www.example.com/broken-url - returns HTTP error 404",
        "target" => [
            "id" => "https://generic-service.com/system",
            "inbox" => "https://generic-service.com/system/inbox/",
            "type" => "Service"
        ],
        "type" => [
            "Flag",
            "coar-notify:UnprocessableNotification"
        ]
    ];
}
?>
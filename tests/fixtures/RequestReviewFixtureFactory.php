<?php

namespace Tests\fixtures;

use Tests\fixtures\BaseFixtureFactory;

class RequestReviewFixtureFactory extends BaseFixtureFactory
{
    public static function source(bool $copy = true)
    {
        if ($copy) {
            return self::deepCopy(self::REQUEST_REVIEW);
        }
        return self::REQUEST_REVIEW;
    }

    public static function invalid()
    {
        $source = self::source();
        self::baseInvalid($source);
        self::actorInvalid($source);
        self::objectInvalid($source);
        return $source;
    }

    private const REQUEST_REVIEW = [
        "@context" => [
            "https://www.w3.org/ns/activitystreams",
            "https://coar-notify.net"
        ],
        "actor" => [
            "id" => "https://orcid.org/0000-0002-1825-0097",
            "name" => "Josiah Carberry",
            "type" => "Person"
        ],
        "id" => "urn:uuid:0370c0fb-bb78-4a9b-87f5-bed307a509dd",
        "object" => [
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
            "type" => [
                "Page",
                "sorg:AboutPage"
            ]
        ],
        "origin" => [
            "id" => "https://research-organisation.org/repository",
            "inbox" => "https://research-organisation.org/inbox/",
            "type" => "Service"
        ],
        "target" => [
            "id" => "https://review-service.com/system",
            "inbox" => "https://review-service.com/inbox/",
            "type" => "Service"
        ],
        "type" => [
            "Offer",
            "coar-notify:ReviewAction"
        ]
    ];
}
?>
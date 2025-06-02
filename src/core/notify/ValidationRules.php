<?php

namespace coarnotify\core\notify;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use const coarnotify\core\activitystreams2\ACTIVITY_STREAMS_OBJECTS;
use coarnotify\validate\Validators;

/**
 * ValidationRules class provides a set of default validation rules for the COAR Notify pattern.
 */
class ValidationRules
{
    public static function defaultValidationRules(): array
    {
        $VALIDATION_RULES = [
            Properties::ID[0] => [
                "default" => function ($obj, $url) {
                    Validators::absolueURI($obj, $url);
                },
                "context" => [
                    Properties::CONTEXT[0] => [
                        "default" => function ($obj, $url) {
                            Validators::url($obj, $url);
                        }
                    ],
                    Properties::ORIGIN[0] => [
                        "default" => function ($obj, $url) {
                            Validators::url($obj, $url);
                        }
                    ],
                    Properties::TARGET[0] => [
                        "default" => function ($obj, $url) {
                            Validators::url($obj, $url);
                        }
                    ],
                    NotifyProperties::ITEM[0] => [
                        "default" => function ($obj, $url) {
                            Validators::url($obj, $url);
                        }
                    ]
                ]
            ],
            Properties::TYPE[0] => [
                "default" => function ($obj, $url) {
                    Validators::typeChecker($obj, $url);
                },
                "context" => [
                    Properties::ACTOR[0] => [
                        "default" => Validators::oneOf([
                            ActivityStreamsTypes::SERVICE,
                            ActivityStreamsTypes::APPLICATION,
                            ActivityStreamsTypes::GROUP,
                            ActivityStreamsTypes::ORGANIZATION,
                            ActivityStreamsTypes::PERSON
                        ])
                    ],
                    Properties::OBJECT[0] => [
                        "default" => Validators::atLeastOneOf(ACTIVITY_STREAMS_OBJECTS)
                    ],
                    Properties::CONTEXT[0] => [
                        "default" => Validators::atLeastOneOf(ACTIVITY_STREAMS_OBJECTS)
                    ],
                    NotifyProperties::ITEM[0] => [
                        "default" => Validators::atLeastOneOf(ACTIVITY_STREAMS_OBJECTS)
                    ]
                ]
            ],
            NotifyProperties::CITE_AS[0] => [
                "default" => function ($obj, $url) {
                    Validators::url($obj, $url);
                }
            ],
            NotifyProperties::INBOX[0] => [
                "default" => function ($obj, $url) {
                    Validators::url($obj, $url);
                }
            ],
            Properties::IN_REPLY_TO[0] => [
                "default" => function ($obj, $url) {
                    Validators::absolueURI($obj, $url);
                },
            ],
            Properties::SUBJECT_TRIPLE[0] => [
                "default" => function ($obj, $url) {
                    Validators::absolueURI($obj, $url);
                },
            ],
            Properties::OBJECT_TRIPLE[0] => [
                "default" => function ($obj, $url) {
                    Validators::absolueURI($obj, $url);
                },
            ],
            Properties::RELATIONSHIP_TRIPLE[0] => [
                "default" => function ($obj, $url) {
                    Validators::absolueURI($obj, $url);
                },
            ]
        ];
        return $VALIDATION_RULES;
    }
}
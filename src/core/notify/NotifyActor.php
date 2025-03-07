<?php

namespace coarnotify\core\notify;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\notify\NotifyProperties;

class NotifyActor extends NotifyPatternPart
{
    const DEFAULT_TYPE = ActivityStreamsTypes::SERVICE;
    const ALLOWED_TYPES = [
        self::DEFAULT_TYPE,
        ActivityStreamsTypes::APPLICATION,
        ActivityStreamsTypes::GROUP,
        ActivityStreamsTypes::ORGANIZATION,
        ActivityStreamsTypes::PERSON
    ];

    public function getName(): string
    {
        return $this->getProperty(NotifyProperties::NAME);
    }

    public function setName(string $value)
    {
        $this->setProperty(NotifyProperties::NAME, $value);
    }
}
?>
<?php

namespace coarnotify\core\notify;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\notify\NotifyProperties;

class NotifyService extends NotifyPatternPart
{
    const DEFAULT_TYPE = ActivityStreamsTypes::SERVICE;

    public function getInbox(): string
    {
        return $this->getProperty(NotifyProperties::INBOX);
    }

    public function setInbox(string $value)
    {
        $this->setProperty(NotifyProperties::INBOX, $value);
    }
}

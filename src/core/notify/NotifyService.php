<?php

namespace coarnotify\core\notify;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;

/**
 * Default class to represent a service in the COAR Notify pattern.
 *
 * Services are used to represent ``origin`` and ``target`` properties in the notification patterns
 *
 * Specific patterns may need to extend this class to provide their specific behaviours and validation
 */
class NotifyService extends NotifyPatternPart
{
    /** @var string The default type for a service is ``Service``, but the type can be set to any value */
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

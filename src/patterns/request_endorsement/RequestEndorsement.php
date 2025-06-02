<?php

namespace coarnotify\patterns\request_endorsement;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\NotifyTypes;

/**
 * Pattern to represent a Request Endorsement notification
 * https://coar-notify.net/specification/1.0.1/request-endorsement/
 */
class RequestEndorsement extends NotifyPattern
{
    /** @var array Request Endorsement types, including an ActivityStreams offer and a COAR Notify Endorsement Action */
    const TYPE = [ActivityStreamsTypes::OFFER, NotifyTypes::ENDORSEMENT_ACTION];

    /**
     * Custom getter to retrieve the object property as a RequestEndorsementObject
     *
     * @return RequestEndorsementObject|null
     */
    public function getObject(): ?RequestEndorsementObject
    {
        $o = $this->getProperty(Properties::OBJECT);
        if ($o !== null) {
            return new RequestEndorsementObject(
                $o,
                false,
                $this->validateProperties,
                $this->validators,
                Properties::OBJECT
            );
        }
        return null;
    }
}
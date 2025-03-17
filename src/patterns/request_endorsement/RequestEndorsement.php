<?php

namespace coarnotify\patterns\request_endorsement;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\NotifyTypes;

class RequestEndorsement extends NotifyPattern
{
    const TYPE = [ActivityStreamsTypes::OFFER, NotifyTypes::ENDORSEMENT_ACTION];

    public function getObject(): ?RequestEndorsementObject
    {
        $o = $this->getProperty(Properties::OBJECT);
        if ($o !== null) {
            return new RequestEndorsementObject(
                $o,
                false,
                $this->validateProperties,
                $this->validators,
                Properties::OBJECT,
                $this->propertiesByReference
            );
        }
        return null;
    }
}
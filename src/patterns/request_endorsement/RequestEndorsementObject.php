<?php

namespace coarnotify\patterns\request_endorsement;

use coarnotify\core\notify\NotifyObject;
use coarnotify\core\notify\NotifyProperties;

use coarnotify\core\activitystreams2\Properties;

class RequestEndorsementObject extends NotifyObject
{
    public function getItem(): ?RequestEndorsementItem
    {
        $i = $this->getProperty(NotifyProperties::ITEM);
        if ($i !== null) {
            return new RequestEndorsementItem(
                $i,
                false,
                $this->validateProperties,
                $this->validators,
                NotifyProperties::ITEM,
                $this->propertiesByReference
            );
        }
        return null;
    }
}
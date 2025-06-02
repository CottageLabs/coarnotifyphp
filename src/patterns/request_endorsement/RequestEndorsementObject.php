<?php

namespace coarnotify\patterns\request_endorsement;

use coarnotify\core\notify\NotifyObject;
use coarnotify\core\notify\NotifyProperties;

use coarnotify\core\activitystreams2\Properties;

/**
 * Custom object class for Request Endorsement to provide the custom item getter
 */
class RequestEndorsementObject extends NotifyObject
{
    /**
     * Custom getter to retrieve the item property as a RequestEndorsementItem
     *
     * @return RequestEndorsementItem|null
     */
    public function getItem(): ?RequestEndorsementItem
    {
        $i = $this->getProperty(NotifyProperties::ITEM);
        if ($i !== null) {
            return new RequestEndorsementItem(
                $i,
                false,
                $this->validateProperties,
                $this->validators,
                NotifyProperties::ITEM
            );
        }
        return null;
    }
}
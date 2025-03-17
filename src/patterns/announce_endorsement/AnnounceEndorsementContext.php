<?php

namespace coarnotify\patterns\announce_endorsement;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\core\notify\NotifyObject;
use coarnotify\core\notify\NotifyProperties;

class AnnounceEndorsementContext extends NotifyObject
{
    public function getItem(): ?AnnounceEndorsementItem
    {
        $i = $this->getProperty(NotifyProperties::ITEM);
        if ($i !== null) {
            return new AnnounceEndorsementItem(
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
<?php

namespace coarnotify\patterns\announce_service_result;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\core\notify\NotifyObject;
use coarnotify\core\notify\NotifyProperties;

class AnnounceServiceResultContext extends NotifyObject
{
    public function getItem(): ?AnnounceServiceResultItem
    {
        $i = $this->getProperty(NotifyProperties::ITEM);
        if ($i !== null) {
            return new AnnounceServiceResultItem(
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
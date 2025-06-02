<?php

namespace coarnotify\patterns\announce_service_result;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\core\notify\NotifyObject;
use coarnotify\core\notify\NotifyProperties;

/**
 * Custom object class for Announce Service Result to provide the custom item getter
 */
class AnnounceServiceResultContext extends NotifyObject
{
    /**
     * Custom getter to retrieve the item property as an AnnounceServiceResultItem
     *
     * @return AnnounceServiceResultItem|null
     */
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
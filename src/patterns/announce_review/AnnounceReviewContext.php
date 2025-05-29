<?php

namespace coarnotify\patterns\announce_review;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\core\notify\NotifyObject;
use coarnotify\core\notify\NotifyProperties;

class AnnounceReviewContext extends NotifyObject
{
    public function getItem(): ?AnnounceReviewItem
    {
        $i = $this->getProperty(NotifyProperties::ITEM);
        if ($i !== null) {
            return new AnnounceReviewItem(
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
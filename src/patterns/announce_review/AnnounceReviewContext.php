<?php

namespace coarnotify\patterns\announce_review;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\core\notify\NotifyObject;
use coarnotify\core\notify\NotifyProperties;

/**
 * Custom Context for Announce Review, specifically to return custom
 * Announce Review Item
 */
class AnnounceReviewContext extends NotifyObject
{
    /**
     * Custom getter to retrieve AnnounceReviewItem
     *
     * @return AnnounceReviewItem|null
     */
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
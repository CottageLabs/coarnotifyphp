<?php

namespace coarnotify\patterns\request_review;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\core\notify\NotifyObject;
use coarnotify\core\notify\NotifyProperties;

/**
 * Custom Request Review Object class to return the custom RequestReviewItem class
 */
class RequestReviewObject extends NotifyObject
{
    /**
     * Custom getter to retrieve the item property as a RequestReviewItem
     *
     * @return RequestReviewItem|null
     */
    public function getItem(): ?RequestReviewItem
    {
        $i = $this->getProperty(NotifyProperties::ITEM);
        if ($i !== null) {
            return new RequestReviewItem(
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
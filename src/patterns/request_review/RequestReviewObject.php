<?php

namespace coarnotify\patterns\request_review;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\core\notify\NotifyObject;
use coarnotify\core\notify\NotifyProperties;

class RequestReviewObject extends NotifyObject
{
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
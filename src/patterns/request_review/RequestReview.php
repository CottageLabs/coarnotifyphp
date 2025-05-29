<?php

namespace coarnotify\patterns\request_review;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\NotifyTypes;
use coarnotify\exceptions\ValidationError;

class RequestReview extends NotifyPattern
{
    const TYPE = [ActivityStreamsTypes::OFFER, NotifyTypes::REVIEW_ACTION];

    public function getObject(): ?RequestReviewObject
    {
        $o = $this->getProperty(Properties::OBJECT);
        if ($o !== null) {
            return new RequestReviewObject(
                $o,
                false,
                $this->validateProperties,
                $this->validators,
                Properties::OBJECT
            );
        }
        return null;
    }
}
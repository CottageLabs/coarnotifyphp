<?php

namespace coarnotify\patterns\request_review;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\NotifyTypes;
use coarnotify\exceptions\ValidationError;

/**
 * Pattern to represent a Request Review notification
 * https://coar-notify.net/specification/1.0.0/request-review/
 */
class RequestReview extends NotifyPattern
{
    /** @var array Request Review types, including an ActivityStreams offer and a COAR Notify Review Action */
    const TYPE = [ActivityStreamsTypes::OFFER, NotifyTypes::REVIEW_ACTION];

    /**
     * Custom getter to retrieve the object property as a RequestReviewObject
     *
     * @return RequestReviewObject|null
     */
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
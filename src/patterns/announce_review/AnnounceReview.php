<?php

namespace coarnotify\patterns\announce_review;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\NotifyTypes;

/**
 * Pattern to represent the Announce Review notification
 * https://coar-notify.net/specification/1.0.0/announce-review/
 */
class AnnounceReview extends NotifyPattern
{
    /** @var array Announce Review type, including Acitivity Streams Announce and Notify Review Action */
    const TYPE = [ActivityStreamsTypes::ANNOUNCE, NotifyTypes::REVIEW_ACTION];

    /**
     * Custom getter to retrieve Announce Review object
     *
     * @return AnnounceReviewObject|null
     */
    public function getObject(): ?AnnounceReviewObject
    {
        $o = $this->getProperty(Properties::OBJECT);
        if ($o !== null) {
            return new AnnounceReviewObject(
                $o,
                false,
                $this->validateProperties,
                $this->validators,
                Properties::OBJECT
            );
        }
        return null;
    }

    /**
     * Custom getter to retrieve AnnounceReview Context
     *
     * @return AnnounceReviewContext|null
     */
    public function getContext(): ?AnnounceReviewContext
    {
        $c = $this->getProperty(Properties::CONTEXT);
        if ($c !== null) {
            return new AnnounceReviewContext(
                $c,
                false,
                $this->validateProperties,
                $this->validators,
                Properties::CONTEXT
            );
        }
        return null;
    }

    /**
     * Extends the base validation to make `context` required
     *
     * @return bool
     * @throws ValidationError
     * @throws \coarnotify\exceptions\ValueError
     */
    public function validate(): bool
    {
        $ve = new ValidationError();
        try {
            parent::validate();
        } catch (ValidationError $superve) {
            $ve = $superve;
        }

        $this->requiredAndValidate($ve, Properties::CONTEXT, $this->getContext());

        if ($ve->hasErrors()) {
            throw $ve;
        }

        return true;
    }
}
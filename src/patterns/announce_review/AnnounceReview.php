<?php

namespace coarnotify\patterns\announce_review;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\NotifyTypes;

class AnnounceReview extends NotifyPattern
{
    const TYPE = [ActivityStreamsTypes::ANNOUNCE, NotifyTypes::REVIEW_ACTION];

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
<?php

namespace coarnotify\patterns\announce_review;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyObject;

/**
 * Custom Announce Review Object to apply custom validation for this pattern
 */
class AnnounceReviewObject extends NotifyObject
{
    /**
     * In addition to the base validator this:
     *
     * * Makes type required
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

        $this->requiredAndValidate($ve, Properties::TYPE, $this->getType());

        if ($ve->hasErrors()) {
            throw $ve;
        }

        return true;
    }
}
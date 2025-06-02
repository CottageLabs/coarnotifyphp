<?php

namespace coarnotify\patterns\announce_review;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyItem;
use coarnotify\core\notify\NotifyProperties;

/**
 * Custom AnnounceReviewItem which provides additional validation over the basic NotifyItem
 */
class AnnounceReviewItem extends NotifyItem
{
    /**
     * In addition to the base validator, this:
     *
     * * Reintroduces type validation
     * * make ``mediaType`` a required field
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
        $this->required($ve, NotifyProperties::MEDIA_TYPE, $this->getMediaType());

        if ($ve->hasErrors()) {
            throw $ve;
        }
        return true;
    }
}
<?php

namespace coarnotify\patterns\request_review;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\core\notify\NotifyItem;
use coarnotify\core\notify\NotifyProperties;
use coarnotify\exceptions\ValidationError;

/**
 * Custom Request Review Item class to provide the custom validation
 */
class RequestReviewItem extends NotifyItem
{
    /**
     * Extend the base validation to include the following constraints:
     *
     * * The type property is required and must validate
     * * the ``mediaType`` property is required
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
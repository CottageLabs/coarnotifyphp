<?php

namespace coarnotify\patterns\request_endorsement;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyItem;
use coarnotify\core\notify\NotifyProperties;

/**
 * Custom item class for Request Endorsement to provide the custom validation
 */
class RequestEndorsementItem extends NotifyItem
{
    /**
     * Extend the base validation to include the following constraints:
     *
     * * The item type is required and must validate
     * * The ``mediaType`` property is required
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
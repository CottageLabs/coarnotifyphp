<?php

namespace coarnotify\patterns\announce_service_result;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyObject;

/**
 * Custom object class for Announce Service Result to apply the custom validation
 */
class AnnounceServiceResultObject extends NotifyObject
{
    /**
     * Extend the base validation to include the following constraints:
     *
     *  The object type is required and must validate
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
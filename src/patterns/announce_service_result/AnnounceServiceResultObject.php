<?php

namespace coarnotify\patterns\announce_service_result;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyObject;

class AnnounceServiceResultObject extends NotifyObject
{
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
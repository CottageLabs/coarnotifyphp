<?php

namespace coarnotify\patterns\request_review;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\core\notify\NotifyItem;
use coarnotify\core\notify\NotifyProperties;
use coarnotify\exceptions\ValidationError;

class RequestReviewItem extends NotifyItem
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
        $this->required($ve, NotifyProperties::MEDIA_TYPE, $this->getMediaType());

        if ($ve->hasErrors()) {
            throw $ve;
        }
        return true;
    }
}
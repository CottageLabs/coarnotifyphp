<?php

namespace coarnotify\patterns\announce_service_result;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyItem;
use coarnotify\core\notify\NotifyProperties;

/**
 * Custom item class for Announce Service Result to apply the custom validation
 */
class AnnounceServiceResultItem extends NotifyItem
{
    /**
     * Beyond the base validation, apply the following:
     *
     * * Make type required and avlid
     * * Make the ``mediaType`` required
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
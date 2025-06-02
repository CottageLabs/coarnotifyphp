<?php

namespace coarnotify\patterns\announce_endorsement;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyItem;
use coarnotify\core\notify\NotifyProperties;

/**
 * Announce Endorsement Item, which extends the base NotifyItem to provide
 * additional validation
 */
class AnnounceEndorsementItem extends NotifyItem
{
    /**
     * Extends the base validation with validation custom to Announce Endorsement notifications
     *
     * * Adds type validation, which the base NotifyItem does not apply
     * * Requires the ``mediaType`` value
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
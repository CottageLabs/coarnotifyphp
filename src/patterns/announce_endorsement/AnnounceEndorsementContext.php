<?php

namespace coarnotify\patterns\announce_endorsement;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\core\notify\NotifyObject;
use coarnotify\core\notify\NotifyProperties;

/**
 * Announce Endorsement context object, which extends the base NotifyObject
 * to allow us to pass back a custom `AnnounceEndorsementItem`
 */
class AnnounceEndorsementContext extends NotifyObject
{
    /**
     * Get a custom AnnounceEndorsementItem
     *
     * @return AnnounceEndorsementItem|null
     */
    public function getItem(): ?AnnounceEndorsementItem
    {
        $i = $this->getProperty(NotifyProperties::ITEM);
        if ($i !== null) {
            return new AnnounceEndorsementItem(
                $i,
                false,
                $this->validateProperties,
                $this->validators,
                NotifyProperties::ITEM
            );
        }
        return null;
    }
}
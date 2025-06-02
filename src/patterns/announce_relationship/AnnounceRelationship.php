<?php

namespace coarnotify\patterns\announce_relationship;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\NotifyTypes;

/**
 * Pattern to represent an Announce Relationship notification
 * https://coar-notify.net/specification/1.0.1/announce-relationship/
 */
class AnnounceRelationship extends NotifyPattern
{
    const TYPE = [ActivityStreamsTypes::ANNOUNCE, NotifyTypes::RELATIONSHIP_ACTION];

    public function getObject(): ?AnnounceRelationshipObject
    {
        /**
         * Custom getter to retrieve the object property as an AnnounceRelationshipObject
         */
        $o = $this->getProperty(Properties::OBJECT);
        if ($o !== null) {
            return new AnnounceRelationshipObject(
                $o,
                false,
                $this->validateProperties,
                $this->validators,
                Properties::OBJECT
            );
        }
        return null;
    }

    /**
     * Extends the base validation to make `context` required
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

        $this->requiredAndValidate($ve, Properties::CONTEXT, $this->getContext());

        if ($ve->hasErrors()) {
            throw $ve;
        }

        return true;
    }
}
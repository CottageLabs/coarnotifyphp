<?php

namespace coarnotify\patterns\announce_endorsement;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\NotifyTypes;

/**
 * Class to represet an Announce Endorsement notification
 * https://coar-notify.net/specification/1.0.1/announce-endorsement/
 */
class AnnounceEndorsement extends NotifyPattern
{
    const TYPE = [ActivityStreamsTypes::ANNOUNCE, NotifyTypes::ENDORSEMENT_ACTION];

    /**
     * Get a context specific to AnnounceEndorsement
     *
     * @return AnnounceEndorsementContext|null
     */
    public function getContext(): ?AnnounceEndorsementContext
    {
        $c = $this->getProperty(Properties::CONTEXT);
        if ($c !== null) {
            return new AnnounceEndorsementContext(
                $c,
                false,
                $this->validateProperties,
                $this->validators,
                Properties::CONTEXT
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
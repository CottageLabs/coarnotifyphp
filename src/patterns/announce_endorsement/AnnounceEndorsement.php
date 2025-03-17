<?php

namespace coarnotify\patterns\announce_endorsement;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\NotifyTypes;

class AnnounceEndorsement extends NotifyPattern
{
    const TYPE = [ActivityStreamsTypes::ANNOUNCE, NotifyTypes::ENDORSEMENT_ACTION];

    public function getContext(): ?AnnounceEndorsementContext
    {
        $c = $this->getProperty(Properties::CONTEXT);
        if ($c !== null) {
            return new AnnounceEndorsementContext(
                $c,
                false,
                $this->validateProperties,
                $this->validators,
                Properties::CONTEXT,
                $this->propertiesByReference
            );
        }
        return null;
    }

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
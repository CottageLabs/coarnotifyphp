<?php

namespace coarnotify\patterns\announce_service_result;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyPattern;

class AnnounceServiceResult extends NotifyPattern
{
    const TYPE = ActivityStreamsTypes::ANNOUNCE;

    public function getObject(): ?AnnounceServiceResultObject
    {
        $o = $this->getProperty(Properties::OBJECT);
        if ($o !== null) {
            return new AnnounceServiceResultObject(
                $o,
                false,
                $this->validateProperties,
                $this->validators,
                Properties::OBJECT
            );
        }
        return null;
    }

    public function getContext(): ?AnnounceServiceResultContext
    {
        $c = $this->getProperty(Properties::CONTEXT);
        if ($c !== null) {
            return new AnnounceServiceResultContext(
                $c,
                false,
                $this->validateProperties,
                $this->validators,
                Properties::CONTEXT
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
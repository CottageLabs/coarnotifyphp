<?php

namespace coarnotify\patterns\announce_service_result;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyPattern;

/**
 * Pattern to represent the Announce Service Result notification
 * https://coar-notify.net/specification/1.0.0/announce-resource/
 */
class AnnounceServiceResult extends NotifyPattern
{
    /**Announce Service Result type, the ActivityStreams Announce type**/
    const TYPE = ActivityStreamsTypes::ANNOUNCE;

    /**
     * Custom getter to retrieve the object property as an AnnounceServiceResultObject
     *
     * @return AnnounceServiceResultObject|null
     */
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

    /**
     * Custom getter to retrieve the context property as an AnnounceServiceResultContext
     *
     * @return AnnounceServiceResultContext|null
     */
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
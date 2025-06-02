<?php

namespace coarnotify\patterns\accept;

use coarnotify\core\notify\NestedPatternObjectTrait;
use coarnotify\core\notify\NotifyPattern;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\core\activitystreams2\ActivityStreamsTypes;

/**
 * Class to represent an Accept notification
 * https://coar-notify.net/specification/1.0.0/accept/
 */
class Accept extends NotifyPattern
{
    use NestedPatternObjectTrait;

    /**
     * The Accept type
     */
    const TYPE = ActivityStreamsTypes::ACCEPT;

    /**
     * Validate the Accept pattern.
     *
     * In addition to the base validation, this:
     * - Makes `inReplyTo` required
     * - Requires the `inReplyTo` value to be the same as the `object.id` value
     *
     * @return bool True if valid, otherwise throws a ValidationError
     * @throws ValidationError
     */
    public function validate(): bool
    {
        $ve = new ValidationError();
        try {
            parent::validate();
        } catch (ValidationError $superve) {
            $ve = $superve;
        }

        // Technically, no need to validate the value, as this is handled by the superclass,
        // but leaving it in for completeness
        $this->requiredAndValidate($ve, Properties::IN_REPLY_TO, $this->getInReplyTo());

        $objid = $this->getObject() ? $this->getObject()->getId() : null;
        if ($this->getInReplyTo() !== $objid) {
            $ve->addError(Properties::canonicalName(Properties::IN_REPLY_TO),
                "Expected inReplyTo id to be the same as the nested object id. inReplyTo: {$this->getInReplyTo()}, object.id: {$objid}");
        }

        if ($ve->hasErrors()) {
            throw $ve;
        }

        return true;
    }
}
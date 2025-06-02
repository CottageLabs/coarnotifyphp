<?php

namespace coarnotify\patterns\undo_offer;

use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\NestedPatternObjectTrait;
use coarnotify\core\notify\SummaryTrait;
use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;

/**
 * Pattern to represent the Undo Offer notification
 * https://coar-notify.net/specification/1.0.0/undo-offer/
 */
class UndoOffer extends NotifyPattern
{
    use NestedPatternObjectTrait, SummaryTrait;

    /** @var string Undo Offer type, the ActivityStreams Undo type */
    const TYPE = ActivityStreamsTypes::UNDO;

    /**
     * In addition to the base validation apply the following constraints:
     *
     * * The ``inReplyTo`` property is required
     * * The ``inReplyTo`` value must match the ``object.id`` value
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

        $this->requiredAndValidate($ve, Properties::IN_REPLY_TO, $this->getInReplyTo());

        $objid = $this->getObject() ? $this->getObject()->getId() : null;
        if ($this->getInReplyTo() !== $objid) {
            $pn = Properties::canonicalName(Properties::IN_REPLY_TO);
            $ve->addError($pn, "Expected inReplyTo id to be the same as the nested object id. inReplyTo: {$this->getInReplyTo()}, object.id: {$objid}");
        }

        if ($ve->hasErrors()) {
            throw $ve;
        }

        return true;
    }
}
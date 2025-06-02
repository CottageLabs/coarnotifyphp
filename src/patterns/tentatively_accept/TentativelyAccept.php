<?php

namespace coarnotify\patterns\tentatively_accept;

use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\SummaryTrait;
use coarnotify\core\notify\NestedPatternObjectTrait;
use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;

/**
 * Pattern to represent a Tentative Accept notification
 * https://coar-notify.net/specification/1.0.0/tentative-accept/
 */
class TentativelyAccept extends NotifyPattern
{
    use NestedPatternObjectTrait, SummaryTrait;

    /** @var string Tentative Accept type, the ActivityStreams Tentative Accept type */
    const TYPE = ActivityStreamsTypes::TENTATIVE_ACCEPT;

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
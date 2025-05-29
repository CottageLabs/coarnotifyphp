<?php

namespace coarnotify\patterns\tentatively_accept;

use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\SummaryTrait;
use coarnotify\core\notify\NestedPatternObjectTrait;
use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;

class TentativelyAccept extends NotifyPattern
{
    use NestedPatternObjectTrait, SummaryTrait;

    const TYPE = ActivityStreamsTypes::TENTATIVE_ACCEPT;

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
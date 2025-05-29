<?php

namespace coarnotify\patterns\unprocessable_notification;

use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\SummaryTrait;
use coarnotify\core\notify\NotifyTypes;
use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;

class UnprocessableNotification extends NotifyPattern
{
    use SummaryTrait;

    const TYPE = [ActivityStreamsTypes::FLAG, NotifyTypes::UNPROCESSABLE_NOTIFICATION];

    public function validate(): bool
    {
        $ve = new ValidationError();
        try {
            parent::validate();
        } catch (ValidationError $superve) {
            $ve = $superve;
        }

        $this->requiredAndValidate($ve, Properties::IN_REPLY_TO, $this->getInReplyTo());
        $this->required($ve, Properties::SUMMARY, $this->getSummary());

        if ($ve->hasErrors()) {
            throw $ve;
        }

        return true;
    }
}
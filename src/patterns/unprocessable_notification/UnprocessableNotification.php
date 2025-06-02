<?php

namespace coarnotify\patterns\unprocessable_notification;

use coarnotify\core\notify\NotifyPattern;
use coarnotify\core\notify\SummaryTrait;
use coarnotify\core\notify\NotifyTypes;
use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;

/**
 * Pattern to represent the Unprocessable Notification notification
 * https://coar-notify.net/specification/1.0.1/unprocessable/
 */
class UnprocessableNotification extends NotifyPattern
{
    use SummaryTrait;

    /** @var array Unprocessable Notification types, including an ActivityStreams Flag and a COAR Notify Unprocessable Notification */
    const TYPE = [ActivityStreamsTypes::FLAG, NotifyTypes::UNPROCESSABLE_NOTIFICATION];

    /**
     * In addition to the base validation apply the following constraints:
     *
     * * The ``inReplyTo`` property is required
     * * The ``summary`` property is required
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
        $this->required($ve, Properties::SUMMARY, $this->getSummary());

        if ($ve->hasErrors()) {
            throw $ve;
        }

        return true;
    }
}
<?php

namespace coarnotify\core\notify;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;

class NotifyItem extends NotifyPatternPart
{
    public function getMediaType(): string
    {
        return $this->getProperty(NotifyProperties::MEDIA_TYPE);
    }

    public function setMediaType(string $value)
    {
        $this->setProperty(NotifyProperties::MEDIA_TYPE, $value);
    }

    public function validate(): bool
    {
        $ve = new ValidationError();

        $this->requiredAndValidate($ve, Properties::ID, $this->getId());

        if ($ve->hasErrors()) {
            throw $ve;
        }
        return true;
    }
}

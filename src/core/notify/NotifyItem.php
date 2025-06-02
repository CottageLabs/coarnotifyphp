<?php

namespace coarnotify\core\notify;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;

/**
 * Defult class to represent an item in the COAR Notify pattern.
 * Items are used to represent the ``ietf:item`` property in the notification patterns
 *
 * Specific patterns may need to extend this class to provide their specific behaviours and validation
 */
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

    /**
     * Validate the item.  This overrides the base validation, as objects only absolutely require an ``id`` property,
     * so the base requirement for a ``type`` is relaxed.
     *
     * @return bool
     * @throws ValidationError
     * @throws \coarnotify\exceptions\ValueError
     */
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

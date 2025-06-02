<?php

namespace coarnotify\core\notify;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;

/**
 * Deafult class to represents an actor in the COAR Notify pattern.
 * Actors are used to represent the ``actor`` property in the notification patterns
 *
 * Specific patterns may need to extend this class to provide their specific behaviours and validation
 */
class NotifyActor extends NotifyPatternPart
{
    /** Default type is ``Service``, but can also be set as any one of the other allowed types */
    const DEFAULT_TYPE = ActivityStreamsTypes::SERVICE;

    //** The allowed types for an actor: ``Service``, ``Application``, ``Group``, ``Organisation``, ``Person`` */
    const ALLOWED_TYPES = [
        self::DEFAULT_TYPE,
        ActivityStreamsTypes::APPLICATION,
        ActivityStreamsTypes::GROUP,
        ActivityStreamsTypes::ORGANIZATION,
        ActivityStreamsTypes::PERSON
    ];

    /**
     * Get the name property of the actor
     * @return string
     */
    public function getName(): string
    {
        return $this->getProperty(NotifyProperties::NAME);
    }

    /**
     * Set the name property of the actor
     *
     * @param string $value The name of the actor
     */
    public function setName(string $value)
    {
        $this->setProperty(NotifyProperties::NAME, $value);
    }
}
?>
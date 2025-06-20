<?php
/**
 * This trait provides methods to handle nested pattern objects in COAR Notify patterns.
 * It allows retrieval and setting of the object property, ensuring it is correctly typed.
 *
 * @package coarnotify\core\notify
 */
namespace coarnotify\core\notify;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\factory\COARNotifyFactory;

trait NestedPatternObjectTrait
{
    /**
     * Retrieve an object as it's correctly typed pattern, falling back to a default `NotifyObject` if no pattern matches.
     *
     * @return NotifyPattern|NotifyObject|null
     */
    public function getObject()
    {
        $o = $this->getProperty(Properties::OBJECT);
        if ($o !== null) {
            $nested = COARNotifyFactory::getByObject(
                $o,
                false,
                $this->validateProperties,
                $this->validators,
                null
            );

            if ($nested !== null) {
                return $nested;
            }

            // If we are unable to construct the typed nested object, just return a generic object
            return new NotifyObject(
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
     * Set the object property.
     *
     * @param NotifyObject|NotifyPattern $value
     */
    public function setObject($value)
    {
        $this->setProperty(Properties::OBJECT, $value->getDoc());
    }
}
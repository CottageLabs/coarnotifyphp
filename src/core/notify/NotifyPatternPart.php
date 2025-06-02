<?php

namespace coarnotify\core\notify;

use coarnotify\core\activitystreams2\Properties;

/**
 * Base class for all pattern parts, such as objects, contexts, actors, etc
 *
 * If there is a default type specified, and a type is not given at construction, then
 * the default type will be added
 */
class NotifyPatternPart extends NotifyBase
{
    const DEFAULT_TYPE = null;
    const ALLOWED_TYPES = [];

    /**
     * Constructor for the NotifyPatternPart
     *
     * If there is a default type specified, and a type is not given at construction, then
     * the default type will be added
     *
     * @param $stream
     * @param $validate_stream_on_construct
     * @param $validate_properties
     * @param $validators
     * @param $validation_context
     */
    public function __construct($stream = null, $validate_stream_on_construct = true, $validate_properties = true, $validators = null, $validation_context = null)
    {
        parent::__construct($stream, $validate_stream_on_construct, $validate_properties, $validators, $validation_context);
        if ($this::DEFAULT_TYPE !== null && $this->getType() === null) {
            $this->setType($this::DEFAULT_TYPE);
        }
    }

    public function setType($types)
    {
        if (!is_array($types)) {
            $types = [$types];
        }

        if (!empty($this::ALLOWED_TYPES)) {
            foreach ($types as $t) {
                if (!in_array($t, $this::ALLOWED_TYPES)) {
                    throw new \InvalidArgumentException("Type value $t is not one of the permitted values");
                }
            }
        }

        if (count($types) === 1) {
            $types = $types[0];
        }

        $this->setProperty(Properties::TYPE, $types);
    }
}


<?php

namespace coarnotify\core\notify;

use coarnotify\core\activitystreams2\Properties;

class NotifyPatternPart extends NotifyBase
{
    const DEFAULT_TYPE = null;
    const ALLOWED_TYPES = [];

    public function __construct($stream = null, $validate_stream_on_construct = true, $validate_properties = true, $validators = null, $validation_context = null, $properties_by_reference = true)
    {
        parent::__construct($stream, $validate_stream_on_construct, $validate_properties, $validators, $validation_context, $properties_by_reference);
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


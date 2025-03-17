<?php

namespace coarnotify\core\notify;

use coarnotify\core\activitystreams2\ActivityStreamsTypes;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;

class NotifyPattern extends NotifyBase
{
    const TYPE = ActivityStreamsTypes::OBJECT;

    public function __construct($stream = null,
                                $validate_stream_on_construct = true,
                                $validate_properties = true,
                                $validators = null,
                                $validation_context = null,
                                $properties_by_reference = true)
    {
        parent::__construct($stream, $validate_stream_on_construct, $validate_properties, $validators, $validation_context, $properties_by_reference);
        $this->_ensureTypeContains($this::TYPE);
    }

    private function _ensureTypeContains($types)
    {
        $existing = $this->stream->getProperty(Properties::TYPE);
        if ($existing === null) {
            $this->setProperty(Properties::TYPE, $types);
        } else {
            if (!is_array($existing)) {
                $existing = [$existing];
            }
            if (!is_array($types)) {
                $types = [$types];
            }
            foreach ($types as $t) {
                if (!in_array($t, $existing)) {
                    $existing[] = $t;
                }
            }
            if (count($existing) === 1) {
                $existing = $existing[0];
            }
            $this->setProperty(Properties::TYPE, $existing);
        }
    }

    public function getOrigin()
    {
        $o = $this->getProperty(Properties::ORIGIN);
        if ($o !== null) {
            return new NotifyService($o, false, $this->getValidateProperties(), $this->getValidators(), Properties::ORIGIN, $this->propertiesByReference);
        }
        return null;
    }

    public function setOrigin($value)
    {
        $this->setProperty(Properties::ORIGIN, $value->getDoc());
    }

    public function getTarget()
    {
        $t = $this->getProperty(Properties::TARGET);
        if ($t !== null) {
            return new NotifyService($t, false, $this->getValidateProperties(), $this->getValidators(), Properties::TARGET, $this->propertiesByReference);
        }
        return null;
    }

    public function setTarget($value)
    {
        $this->setProperty(Properties::TARGET, $value->getDoc());
    }

    public function getObject()
    {
        $o = $this->getProperty(Properties::OBJECT);
        if ($o !== null) {
            return new NotifyObject($o, false, $this->getValidateProperties(), $this->getValidators(), Properties::OBJECT, $this->propertiesByReference);
        }
        return null;
    }

    public function setObject($value)
    {
        $this->setProperty(Properties::OBJECT, $value->getDoc());
    }

    public function getInReplyTo()
    {
        return $this->getProperty(Properties::IN_REPLY_TO);
    }

    public function setInReplyTo($value)
    {
        $this->setProperty(Properties::IN_REPLY_TO, $value);
    }

    public function getActor()
    {
        $a = $this->getProperty(Properties::ACTOR);
        if ($a !== null) {
            return new NotifyActor($a, false, $this->getValidateProperties(), $this->getValidators(), Properties::ACTOR, $this->propertiesByReference);
        }
        return null;
    }

    public function setActor($value)
    {
        $this->setProperty(Properties::ACTOR, $value->getDoc());
    }

    public function getContext()
    {
        $c = $this->getProperty(Properties::CONTEXT);
        if ($c !== null) {
            return new NotifyObject($c, false, $this->getValidateProperties(), $this->getValidators(), Properties::CONTEXT, $this->propertiesByReference);
        }
        return null;
    }

    public function setContext($value)
    {
        $this->setProperty(Properties::CONTEXT, $value->getDoc());
    }

    public function validate(): bool
    {
        $ve = new ValidationError();
        try {
            parent::validate();
        } catch (ValidationError $superve) {
            $ve = $superve;
        }

        $this->requiredAndValidate($ve, Properties::ORIGIN, $this->getOrigin());
        $this->requiredAndValidate($ve, Properties::TARGET, $this->getTarget());
        $this->requiredAndValidate($ve, Properties::OBJECT, $this->getObject());
        $this->optionalAndValidate($ve, Properties::ACTOR, $this->getActor());
        $this->optionalAndValidate($ve, Properties::IN_REPLY_TO, $this->getInReplyTo());
        $this->optionalAndValidate($ve, Properties::CONTEXT, $this->getContext());

        if ($ve->hasErrors()) {
            throw $ve;
        }

        return true;
    }
}
?>
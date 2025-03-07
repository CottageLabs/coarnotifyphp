<?php

namespace coarnotify\core\notify;

use coarnotify\core\activitystreams2\ActivityStream;
use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValueError;
use coarnotify\validate\Validator;
use coarnotify\exceptions\ValidationError;

use const coarnotify\validate\REQUIRED_MESSAGE;

use coarnotify\core\notify\ValidationRules;

/**
 * Base class from which all Notify objects extend.
 *
 * There are two kinds of Notify objects:
 *
 * 1. Patterns, which are the notifications themselves
 * 2. Pattern Parts, which are nested elements in the Patterns, such as objects, contexts, actors, etc
 *
 * This class forms the basis for both of those types, and provides essential services,
 * such as construction, accessors and validation, as well as supporting the essential
 * properties "id" and "type"
 */
class NotifyBase
{
    protected $validateStreamOnConstruct;
    protected $validateProperties;
    protected $validators;
    protected $validationContext;
    protected $propertiesByReference;
    protected $stream;

    /**
     * Base constructor that all subclasses should call
     *
     * @param mixed $stream The activity stream object, or an array from which one can be created
     * @param bool $validate_stream_on_construct Should the incoming stream be validated at construction-time
     * @param bool $validate_properties Should individual properties be validated as they are set
     * @param Validator|null $validators The validator object for this class and all nested elements
     * @param string|array|null $validation_context The context in which this object is being validated
     * @param bool $properties_by_reference Should properties be get and set by reference or by value
     */
    public function __construct($stream = null, $validate_stream_on_construct = true, $validate_properties = true, $validators = null, $validation_context = null, $properties_by_reference = true)
    {
        $this->validateStreamOnConstruct = $validate_stream_on_construct;
        $this->validateProperties = $validate_properties;
        $this->validators = $validators ?? new Validator(ValidationRules::defaultValidationRules());
        $this->validationContext = $validation_context;
        $this->propertiesByReference = $properties_by_reference;
        $validate_now = false;

        if ($stream === null) {
            $this->stream = new ActivityStream();
        } elseif (is_array($stream)) {
            $validate_now = $validate_stream_on_construct;
            $this->stream = new ActivityStream($stream);
        } else {
            $validate_now = $validate_stream_on_construct;
            $this->stream = $stream;
        }

        if ($this->stream->getProperty(Properties::ID) === null) {
            $this->stream->setProperty(Properties::ID, "urn:uuid:" . uniqid());
        }

        if ($validate_now) {
            $this->validate();
        }
    }

    public function getValidateProperties(): bool
    {
        return $this->validateProperties;
    }

    public function getValidateStreamOnConstruct(): bool
    {
        return $this->validateStreamOnConstruct;
    }

    public function getValidators(): Validator
    {
        return $this->validators;
    }

    public function getDoc()
    {
        return $this->stream->getDoc();
    }

    public function getId()
    {
        return $this->getProperty(Properties::ID);
    }

    public function setId($value)
    {
        $this->setProperty(Properties::ID, $value);
    }

    public function getType()
    {
        return $this->getProperty(Properties::TYPE);
    }

    public function setType($types)
    {
        $this->setProperty(Properties::TYPE, $types);
    }

    public function getProperty($prop_name, $by_reference = null)
    {
        if ($by_reference === null) {
            $by_reference = $this->propertiesByReference;
        }
        $val = $this->stream->getProperty($prop_name);
        return $by_reference ? $val : clone $val;
    }

    public function setProperty($prop_name, $value, $by_reference = null)
    {
        if ($by_reference === null) {
            $by_reference = $this->propertiesByReference;
        }
        $this->validateProperty($prop_name, $value);
        if (!$by_reference) {
            $value = clone $value;
        }
        $this->stream->setProperty($prop_name, $value);
    }

    public function validate(): bool
    {
        $ve = new ValidationError();

        $this->requiredAndValidate($ve, Properties::ID, $this->getId());
        $this->requiredAndValidate($ve, Properties::TYPE, $this->getType());

        if ($ve->hasErrors()) {
            throw $ve;
        }
        return true;
    }

    public function validateProperty($prop_name, $value, $force_validate = false, $raise_error = true)
    {
        if ($value === null) {
            return [true, ""];
        }
        if ($this->getValidateProperties() || $force_validate) {
            $validator = $this->getValidators()->get($prop_name, $this->validationContext);
            if ($validator !== null) {
                try {
                    $validator($this, $value);
                } catch (ValueError $ve) {
                    if ($raise_error) {
                        throw $ve;
                    } else {
                        return [false, $ve->getMessage()];
                    }
                }
            }
        }
        return [true, ""];
    }

    protected function _registerPropertyValidationError(ValidationError $ve, $prop_name, $value)
    {
        list($e, $msg) = $this->validateProperty($prop_name, $value, true, false);
        if (!$e) {
            $pn = Properties::canonicalName($prop_name);
            $ve->addError($pn, $msg);
        }
    }

    protected function required(ValidationError $ve, $prop_name, $value)
    {
        if ($value === null) {
            $pn = Properties::canonicalName($prop_name);
            // $pn = is_array($prop_name) ? $prop_name[0] : $prop_name;
            $ve->addError($prop_name, str_replace("{x}", $pn, REQUIRED_MESSAGE));
        }
    }

    protected function requiredAndValidate(ValidationError $ve, $prop_name, $value)
    {
        $pn = Properties::canonicalName($prop_name);
        if ($value === null) {
            # $pn = is_array($prop_name) ? $prop_name[0] : $prop_name;
            $ve->addError($pn, str_replace("{x}", $pn, REQUIRED_MESSAGE));
        } else {
            if ($value instanceof NotifyBase) {
                try {
                    $value->validate();
                } catch (ValidationError $subve) {
                    $ve->addNestedErrors($pn, $subve);
                }
            } else {
                $this->_registerPropertyValidationError($ve, $prop_name, $value);
            }
        }
    }

    protected function optionalAndValidate(ValidationError $ve, $prop_name, $value)
    {
        if ($value !== null) {
            if ($value instanceof NotifyBase) {
                try {
                    $value->validate();
                } catch (ValidationError $subve) {
                    $ve->addNestedErrors($prop_name, $subve);
                }
            } else {
                $this->_registerPropertyValidationError($ve, $prop_name, $value);
            }
        }
    }

    public function toJSONLD(): array
    {
        return $this->stream->toJSONLD();
    }
}

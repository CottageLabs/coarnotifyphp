<?php

namespace coarnotify\validate;

use coarnotify\core\activitystreams2\Properties;

const REQUIRED_MESSAGE = "`{x}` is a required field";

/**
 * A wrapper around a set oaf validation rules which can be used to select the appropriate validator
 * in a given context.
 *
 * The validation rules are structured as follows:
 *```
 *
 * {
 *  "<property>": {
 *      "default": default_validator_function
 *      "context": {
 *          "<context>": {
 *              "default": default_validator_function
 *          }
 *      }
 *  }
 * }
 * ```
 *
 * Here the ``<property>`` key is the name of the property being validated, which may be a string (the property name)
 * or a ``tuple`` of strings (the property name and the namespace for the property name).
 *
 * If a ``context`` is provided, then if the top level property is being validated, and it appears inside a field
 * present in the ``context`` then the ``default`` validator at the top level is overridden by the ``default`` validator
 * in the ``context``.
 *
 * For example, consider the following rules:
 *
 * ```
 * {
 *  Properties.TYPE: {
 *      "default": validate.typeChecker,
 *      "context": {
 *          Properties.ACTOR: {
 *              "default": validate.one_of([
 *                  ActivityStreamsTypes.SERVICE,
 *                  ActivityStreamsTypes.APPLICATION
 *              ])
 *          }
 *      }
 *  }
 * }
 * ```
 *
 * This tells us that the ``TYPE`` property should be validated with ``validate.typeChecker`` by default.  But if
 * we are looking at that ``TYPE`` property inside an ``ACTOR`` object, then instead we should use ``validate.one_of``.
 *
 * When the `get` method is called, the ``context`` parameter can be used to specify the context in which the
 * property is being validated.
 */
class Validator
{
    private $_rules;

    /**
     * Create a new validator with the given rules
     *
     * @param array $rules The rules to use for validation
     */
    public function __construct(array $rules)
    {
        $this->_rules = $rules;
    }

    /**
     * Get the validation function for the given property in the given context
     *
     * @param string|array $property The property to get the validation function for
     * @param string|array|null $context The context in which the property is being validated
     * @return callable|null A function which can be used to validate the property
     */
    public function get($property, $context = null)
    {
        if (is_array($property)) {
            $property = $property[0];
        }
        // $pn = Properties::canonicalName($property);
        $default = $this->_rules[$property]['default'] ?? null;
        if ($context !== null) {
            if (is_array($context)) {
                $context = $context[0];
            }
            $specific = $this->_rules[$property]['context'][$context]['default'] ?? null;
            if ($specific !== null) {
                return $specific;
            }
        }
        return $default;
    }

    /**
     * The ruleset for this validator
     *
     * @return array
     */
    public function rules()
    {
        return $this->_rules;
    }

    /**
     * Add new rules to the existing ruleset
     *
     * @param array $rules
     */
    public function addRules(array $rules)
    {
        $existing = $this->rules();

        $this->_rules = $this->mergeDictsRecursive($existing, $rules);
    }

    private function mergeDictsRecursive(array $dict1, array $dict2)
    {
        $merged = $dict1;
        foreach ($dict2 as $key => $value) {
            if (isset($merged[$key]) && is_array($merged[$key]) && is_array($value)) {
                $merged[$key] = $this->mergeDictsRecursive($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }
        return $merged;
    }
}


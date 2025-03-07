<?php

namespace coarnotify\exceptions;

/**
 * Exception class for validation errors.
 *
 * This class is designed to be thrown and caught and to collect validation errors
 * as it passes through the validation pipeline.
 */
class ValidationError extends NotifyException
{
    private $_errors;

    /**
     * Constructor for ValidationError
     *
     * @param array|null $errors A dictionary of errors to construct the exception around
     */
    public function __construct(array $errors = null)
    {
        parent::__construct();
        $this->_errors = $errors ?? [];
    }

    /**
     * Get the dictionary of errors
     *
     * @return array The dictionary of errors
     */
    public function getErrors(): array
    {
        return $this->_errors;
    }

    /**
     * Record an error on the supplied key with the message value
     *
     * @param string $key The key for which an error is to be recorded
     * @param string $value The error message
     */
    public function addError(string $key, string $value)
    {
        if (!isset($this->_errors[$key])) {
            $this->_errors[$key] = ["errors" => []];
        }
        $this->_errors[$key]["errors"][] = $value;
    }

    /**
     * Take an existing ValidationError and add it as a nested set of errors under the supplied key
     *
     * @param string $key The key under which all the nested validation errors should go
     * @param ValidationError $subve The existing ValidationError object
     */
    public function addNestedErrors(string $key, ValidationError $subve)
    {
        if (!isset($this->_errors[$key])) {
            $this->_errors[$key] = ["errors" => []];
        }
        if (!isset($this->_errors[$key]["nested"])) {
            $this->_errors[$key]["nested"] = [];
        }

        foreach ($subve->getErrors() as $k => $v) {
            $this->_errors[$key]["nested"][$k] = $v;
        }
    }

    /**
     * Are there any errors registered
     *
     * @return bool True if there are errors, false otherwise
     */
    public function hasErrors(): bool
    {
        return count($this->_errors) > 0;
    }

    /**
     * Convert the errors to a string
     *
     * @return string The string representation of the errors
     */
    public function __toString(): string
    {
        return json_encode($this->_errors);
    }
}
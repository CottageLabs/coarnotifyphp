<?php
/**
 * This module contains everything COAR Notify needs to know about ActivityStreams 2.0
 * https://www.w3.org/TR/activitystreams-core/
 *
 * It provides knowledge of the essential AS properties and types, and a class to wrap
 * ActivityStreams objects and provide a simple interface to work with them.
 *
 * *NOTE** this is not a complete implementation of AS 2.0, it is **only** what is required
 * to work with COAR Notify patterns.
 */
namespace coarnotify\core\activitystreams2;

/**
 * A simple wrapper around an ActivityStreams dictionary object
 *
 * Construct it with a python dictionary that represents an ActivityStreams object, or
 * without to create a fresh, blank object.
 */
class ActivityStream
{

    private $doc;
    private $context;

    /**
     * Construct a new ActivityStream object
     * @param $raw  the raw ActivityStreams object, as a dictionary
     */
    public function __construct($raw = null)
    {
        $this->doc = ($raw == null) ? array() : $raw;
        $this->context = [];
        if (array_key_exists("@context", $this->doc)) {
            $this->context = $this->doc["@context"];
            if (!is_array($this->context)) {
                $this->context = [$this->context];
            }
            unset($this->doc["@context"]);
        }
    }

    /** The internal dictionary representation of the ActivityStream, without the json-ld context**/
    public function getDoc()
    {
        return $this->doc;
    }

    public function setDoc($doc)
    {
        $this->doc = $doc;
    }

    /** The json-ld context of the ActivityStream**/
    public function getContext()
    {
        return $this->context;
    }

    public function setContext($context)
    {
        $this->context = $context;
    }

    /** Register a namespace in the context of the ActivityStream **/
    private function _registerNamespace($namespace)
    {
        $entry = $namespace;
        if (is_array($namespace)) {
            $url = $namespace[1];
            $short = $namespace[0];
            $entry = [$short => $url];
        }

        if (!in_array($entry, $this->context)) {
            $this->context[] = $entry;
        }
    }

    /**
     * Set an arbitrary property on the object.  The property name can be one of:
     *
     * * A simple string with the property name
     * * A tuple of the property name and the full namespace ``("name", "http://example.com/ns")``
     * * A tuple containing the property name and another tuple of the short name and the full namespace ``("name", ("as", "http://example.com/ns"))``
     *
     * @param property: the property name
     * @param value: the value to set
     **/
    public function setProperty($property, $value)
    {
        $prop_name = $property;
        $namespace = null;
        if (is_array($property)) {
            $prop_id = $property[0];
            $prop_name = $property[1];
            $namespace = $property[2];
        }

        $this->doc[$prop_name] = $value;
        if ($namespace !== null) {
            $this->_registerNamespace($namespace);
        }
    }

    /**
     * Get an arbitrary property on the object.  The property name can be one of:
     *
     * * A simple string with the property name
     * * A tuple of the property name and the full namespace ``("name", "http://example.com/ns")``
     * * A tuple containing the property name and another tuple of the short name and the full namespace ``("name", ("as", "http://example.com/ns"))``
     *
     * @param property:   the property name
     * @return: the value of the property, or None if it does not exist
     **/
    public function getProperty($property)
    {
        $prop_name = $property;
        $namespace = null;
        if (is_array($property)) {
            $prop_id = $property[0];
            $prop_name = $property[1];
            $namespace = $property[2];
        }

        return isset($this->doc[$prop_name]) ? $this->doc[$prop_name] : null;
    }

    /**  Get the activity stream as a JSON-LD object **/
    public function toJsonLd()
    {
        return array_merge(["@context" => $this->context], $this->doc);
    }
}
<?php
namespace coarnotify\core\activitystreams2;

const ACTIVITY_STREAMS_CONTEXT = "https://www.w3.org/ns/activitystreams";

/**
 * ActivityStreams 2.0 properties used in COAR Notify Patterns
 *
 * These are provided as tuples, where the first element is the property name, and the second element is the namespace.
 *
 * These are suitbale to be used as property names in all the property getters/setters in the notify pattern objects
 * and in the validation configuration.
 */
class Properties
{
    /**``id`` property */
    const ID = ["as_id", "id", ACTIVITY_STREAMS_CONTEXT];

    /** ``type`` property */
    const TYPE = ["as_type", "type", ACTIVITY_STREAMS_CONTEXT];

    /** ``origin`` property */
    const ORIGIN = ["as_origin", "origin", ACTIVITY_STREAMS_CONTEXT];

    /** ``object`` property  */
    const OBJECT = ["as_object", "object", ACTIVITY_STREAMS_CONTEXT];

    /** ``target`` property */
    const TARGET = ["as_target", "target", ACTIVITY_STREAMS_CONTEXT];

    /** ``actor`` property */
    const ACTOR = ["as_actor", "actor", ACTIVITY_STREAMS_CONTEXT];

    /** ``inReplyTo`` property */
    const IN_REPLY_TO = ["as_inReplyTo", "inReplyTo", ACTIVITY_STREAMS_CONTEXT];

    /** ``context`` property */
    const CONTEXT = ["as_context", "context", ACTIVITY_STREAMS_CONTEXT];

    /** ``summary`` property */
    const SUMMARY = ["as_summary", "summary", ACTIVITY_STREAMS_CONTEXT];

    /** ``as:subject`` property  */
    const SUBJECT_TRIPLE = ["as_subject_triple", "as:subject", ACTIVITY_STREAMS_CONTEXT];

    /** ``as:object`` property */
    const OBJECT_TRIPLE = ["as_object_triple", "as:object", ACTIVITY_STREAMS_CONTEXT];

    /** ``as:relationship`` property */
    const RELATIONSHIP_TRIPLE = ["as_relationship_triple", "as:relationship", ACTIVITY_STREAMS_CONTEXT];

    public static function canonicalName($property)
    {
        if (!is_array($property)) {
            return $property;
        }

        $len = count($property);
        if ($len == 1) {
            return $property[0];
        } else if ($len == 2) {
            return $property[1];
        } else if ($len >= 3) {
            return $property[2] . "#" . $property[1];
        }
        return $property[0];
    }
}


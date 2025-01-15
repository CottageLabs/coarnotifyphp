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
    const ID = ["id", ACTIVITY_STREAMS_CONTEXT];

    /** ``type`` property */
    const TYPE = ["type", ACTIVITY_STREAMS_CONTEXT];

    /** ``origin`` property */
    const ORIGIN = ["origin", ACTIVITY_STREAMS_CONTEXT];

    /** ``object`` property  */
    const OBJECT = ["object", ACTIVITY_STREAMS_CONTEXT];

    /** ``target`` property */
    const TARGET = ["target", ACTIVITY_STREAMS_CONTEXT];

    /** ``actor`` property */
    const ACTOR = ["actor", ACTIVITY_STREAMS_CONTEXT];

    /** ``inReplyTo`` property */
    const IN_REPLY_TO = ["inReplyTo", ACTIVITY_STREAMS_CONTEXT];

    /** ``context`` property */
    const CONTEXT = ["context", ACTIVITY_STREAMS_CONTEXT];

    /** ``summary`` property */
    const SUMMARY = ["summary", ACTIVITY_STREAMS_CONTEXT];

    /** ``as:subject`` property  */
    const SUBJECT_TRIPLE = ["as:subject", ACTIVITY_STREAMS_CONTEXT];

    /** ``as:object`` property */
    const OBJECT_TRIPLE = ["as:object", ACTIVITY_STREAMS_CONTEXT];

    /** ``as:relationship`` property */
    const RELATIONSHIP_TRIPLE = ["as:relationship", ACTIVITY_STREAMS_CONTEXT];
}


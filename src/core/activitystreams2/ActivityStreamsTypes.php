<?php

namespace coarnotify\core\activitystreams2;

/**
 * List of all the Activity Streams types COAR Notify may use.
 *
 * Note that COAR Notify also has its own custom types and they are defined in
 * `coarnotify.models.notify.NotifyTypes`
 */
class ActivityStreamsTypes
{
    # Activities
    const ACCEPT = "Accept";
    const ANNOUNCE = "Announce";
    const REJECT = "Reject";
    const OFFER = "Offer";
    const TENTATIVE_ACCEPT = "TentativeAccept";
    const TENTATIVE_REJECT = "TentativeReject";
    const FLAG = "Flag";
    const UNDO = "Undo";

    # Objects
    const ACTIVITY = "Activity";
    const APPLICATION = "Application";
    const ARTICLE = "Article";
    const AUDIO = "Audio";
    const COLLECTION = "Collection";
    const COLLECTION_PAGE = "CollectionPage";
    const RELATIONSHIP = "Relationship";
    const DOCUMENT = "Document";
    const EVENT = "Event";
    const GROUP = "Group";
    const IMAGE = "Image";
    const INTRANSITIVE_ACTIVITY = "IntransitiveActivity";
    const NOTE = "Note";
    const OBJECT = "Object";
    const ORDERED_COLLECTION = "OrderedCollection";
    const ORDERED_COLLECTION_PAGE = "OrderedCollectionPage";
    const ORGANIZATION = "Organization";
    const PAGE = "Page";
    const PERSON = "Person";
    const PLACE = "Place";
    const PROFILE = "Profile";
    const QUESTION = "Question";
    const SERVICE = "Service";
    const TOMBSTONE = "Tombstone";
    const VIDEO = "Video";
}

/**
 * The sub-list of ActivityStreams types that are also objects in AS 2.0
 */
const ACTIVITY_STREAMS_OBJECTS = [
    ActivityStreamsTypes::ACTIVITY,
    ActivityStreamsTypes::APPLICATION,
    ActivityStreamsTypes::ARTICLE,
    ActivityStreamsTypes::AUDIO,
    ActivityStreamsTypes::COLLECTION,
    ActivityStreamsTypes::COLLECTION_PAGE,
    ActivityStreamsTypes::RELATIONSHIP,
    ActivityStreamsTypes::DOCUMENT,
    ActivityStreamsTypes::EVENT,
    ActivityStreamsTypes::GROUP,
    ActivityStreamsTypes::IMAGE,
    ActivityStreamsTypes::INTRANSITIVE_ACTIVITY,
    ActivityStreamsTypes::NOTE,
    ActivityStreamsTypes::OBJECT,
    ActivityStreamsTypes::ORDERED_COLLECTION,
    ActivityStreamsTypes::ORDERED_COLLECTION_PAGE,
    ActivityStreamsTypes::ORGANIZATION,
    ActivityStreamsTypes::PAGE,
    ActivityStreamsTypes::PERSON,
    ActivityStreamsTypes::PLACE,
    ActivityStreamsTypes::PROFILE,
    ActivityStreamsTypes::QUESTION,
    ActivityStreamsTypes::SERVICE,
    ActivityStreamsTypes::TOMBSTONE,
    ActivityStreamsTypes::VIDEO
];

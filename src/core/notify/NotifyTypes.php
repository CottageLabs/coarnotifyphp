<?php
namespace coarnotify\core\notify;

/**
 * List of all the COAR Notify types patterns may use.
 *
 * These are in addition to the base Activity Streams types, which are in `coarnotify\core\activitystreams2\ActivityStreamsTypes`
 */
class NotifyTypes
{
    const ENDORSEMENT_ACTION = "coar-notify:EndorsementAction";
    const INGEST_ACTION = "coar-notify:IngestAction";
    const RELATIONSHIP_ACTION = "coar-notify:RelationshipAction";
    const REVIEW_ACTION = "coar-notify:ReviewAction";
    const UNPROCESSABLE_NOTIFICATION = "coar-notify:UnprocessableNotification";

    const ABOUT_PAGE = "sorg:AboutPage";
}
<?php

namespace coarnotify\core\notify;

const NOTIFY_NAMESPACE = "https://coar-notify.net";

/**
 * COAR Notify properties used in COAR Notify Patterns
 *
 * Most of these are provided as arrays, where the first element is the property name, and the second element is the namespace.
 * Some are provided as plain strings without namespaces.
 *
 * These are suitable to be used as property names in all the property getters/setters in the notify pattern objects
 * and in the validation configuration.
 */
class NotifyProperties
{
    /** ``inbox`` property */
    const INBOX = ["cn_inbox", "inbox", NOTIFY_NAMESPACE];

    /** ``ietf:cite-as`` property */
    const CITE_AS = ["ietf_cite_as", "ietf:cite-as", NOTIFY_NAMESPACE];

    /** ``ietf:item`` property */
    const ITEM = ["ietf_item", "ietf:item", NOTIFY_NAMESPACE];

    /** ``name`` property */
    const NAME = "name";

    /** ``mediaType`` property */
    const MEDIA_TYPE = "mediaType";
}
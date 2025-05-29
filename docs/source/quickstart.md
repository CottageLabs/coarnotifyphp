# Quickstart

## Construct and Send a Notification

You can combine the general object models for the notify patterns with the client module to construct and 
send a notification.

The following example constructs an `AnnounceReview` notification with some basic information. 
See [COAR Notify Specification](https://coar-notify.net/specification/1.0.0/announce-review/) for details.

We create the `AnnounceReview` object, then create the `NotifyActor`, `NotifyObject`, and `NotifyService` objects for 
the key parts of the notification, and attach them to the `AnnounceReview` object.

Finally, we create a `COARNotifyClient` object and send the notification to the target inbox.

```php
use coarnotify\client\COARNotifyClient;
use coarnotify\patterns\announce_review\AnnounceReview;
use coarnotify\core\notify\NotifyActor;
use coarnotify\core\notify\NotifyObject;
use coarnotify\core\notify\NotifyService;
use coarnotify\core\activitystreams2\ActivityStreamsTypes;

$announcement = new AnnounceReview();

$actor = new NotifyActor();
$actor->setId("https://cottagelabs.com/");
$actor->setName("My Review Service");

$obj = new NotifyObject();
$obj->setType(ActivityStreamsTypes::DOCUMENT);
$obj->setCiteAs("https://dx.doi.org/10.12345/6789");

$origin = new NotifyService();
$origin->setId("https://cottagelabs.com/");
$origin->setInbox("https://cottagelabs.com/inbox");

$target = new NotifyService();
$target->setId("https://example.com/");
$target->setInbox("https://example.com/inbox");

$announcement->setActor($actor);
$announcement->setObject($obj);
$announcement->setOrigin($origin);
$announcement->setTarget($target);

$client = new COARNotifyClient();
$response = $client->send($announcement, $target->getInbox());
```

## Parse a Raw Notification

You can receive and parse a raw notification using the object factory `coarnotify\factory\COARNotifyFactory`.

Suppose you have a basic notification consisting of the following JSON string:

```json
{
  "@context": [
    "https://www.w3.org/ns/activitystreams",
    "https://coar-notify.net"
  ],
  "id": "urn:uuid:0370c0fb-bb78-4a9b-87f5-bed307a509dd",
  "object": {
    "id": "https://research-organisation.org/repository/preprint/201203/421/",
    "ietf:item": {
      "id": "https://research-organisation.org/repository/preprint/201203/421/content.pdf",
      "mediaType": "application/pdf",
      "type": [
        "Article",
        "sorg:ScholarlyArticle"
      ]
    },
    "type": [
      "Page",
      "sorg:AboutPage"
    ]
  },
  "origin": {
    "id": "https://research-organisation.org/repository",
    "inbox": "https://research-organisation.org/inbox/",
    "type": "Service"
  },
  "target": {
    "id": "https://overlay-journal.com/system",
    "inbox": "https://overlay-journal.com/inbox/",
    "type": "Service"
  },
  "type": [
    "Offer",
    "coar-notify:EndorsementAction"
  ]
}
```

You can parse this notification as follows:

```php
use coarnotify\factory\COARNotifyFactory;
use coarnotify\patterns\request_endorsement\RequestEndorsement;

$raw = '{ "@context": ... }'; // The raw payload shown above
$data = json_decode($raw, true);
$notification = COARNotifyFactory::getByObject($data);

// Confirm that the payload has been parsed into the correct object
assert($notification instanceof RequestEndorsement);
```

Alternatively, you can access the correct model objects via the type of the notification and construct it yourself:

```php
use coarnotify\factory\COARNotifyFactory;
use coarnotify\patterns\request_endorsement\RequestEndorsement;

$raw = '{ "@context": ... }'; // The raw payload shown above
$data = json_decode($raw, true);
$klazz = COARNotifyFactory::getByType($data['type']);
$notification = new $klazz($data);

// Confirm that the detected class is the correct one
assert($klazz === RequestEndorsement::class);
```
Test Server
===========

This library comes bundled with an extremely basic test server (using Slim) to allow you to send notifications as if they
were going to a real inbox.

The test server is available in ``tests/server`` in the source code.

In order to use the test server you will need to install the ``dev`` dependencies in the ``composer.json`` file

Configuring the Test Server
---------------------------

The test server's default settings are in ``tests/server/settings.php``.

Create a local config file called ``local.php`` containing your local settings.  The test server will automatically
load this file if it exists.  The local config file should be in the same directory as the ``settings.php`` file.

The main things to set are:

* `store_dir`: the directory to store the notifications.  You MUST supply your own path
* `port`: the port to run the server on.  Default is 5005

.. code-block:: python

    store_dir = '/path/to/store/notifications'
    port = 8080

The other properties you may want to override are:

* ``response_status``: which HTTP status code to respond with.  Valid values are `201` (Created) and `202` (Accepted)
* ``validate_incoming`: should the inbox attempt to validate incoming notifications.  Default is `true`


Running and using the Test Server
---------------------------------

Start the server with the following command:

```
php -S localhost:8080 -t tests/server
```

You can then send notifications to the server using the client library, and set the target inbox
to ``http://localhost:8080/inbox``.

The server will store the notifications in the directory you specified in the settings.

Notifications are stored as JSON files in the directory, with the following naming scheme

``{datestamp}_{time}_{uuid}.json``

Where the ``uuid`` is the server's id, not the id supplied in the notification.  If you have the server set to
create notifications (as opposed to accept) then this id will be returned to you in the ``Location`` header of
the server response.


```
client = new COARNotifyClient("http://localhost:8080/inbox")
notification = new RequestReview(data)
resp = client->send(ae)
echo resp.action
echo resp.location
```

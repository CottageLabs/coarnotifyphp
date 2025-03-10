<?php

namespace coarnotify\client;

use coarnotify\core\notify\NotifyPattern;
use coarnotify\http\HttpLayer;
use coarnotify\http\CurlHttpLayer;
use coarnotify\exceptions\NotifyException;
use coarnotify\client\NotifyResponse;

class COARNotifyClient
{
    /**
     * The COAR Notify Client, which is the mechanism through which you will interact with external inboxes.
     *
     * If you do not supply an inbox URL at construction you will
     * need to supply it via the `inbox_url` setter, or when you send a notification.
     *
     * @param string|null $inboxUrl HTTP URI of the inbox to communicate with by default
     * @param HttpLayer|null $httpLayer An implementation of the HttpLayer interface to use for sending HTTP requests.
     *                                  If not provided, the default implementation will be used based on `RequestsHttpLayer`.
     */
    private $inboxUrl;
    private $http;

    public function __construct(?string $inboxUrl = null, ?HttpLayer $httpLayer = null)
    {
        $this->inboxUrl = $inboxUrl;
        $this->http = $httpLayer ?? new CurlHttpLayer();
    }

    /**
     * Get the HTTP URI of the inbox to communicate with by default.
     *
     * @return string|null
     */
    public function getInboxUrl(): ?string
    {
        return $this->inboxUrl;
    }

    /**
     * Set the HTTP URI of the inbox to communicate with by default.
     *
     * @param string $value
     */
    public function setInboxUrl(string $value): void
    {
        $this->inboxUrl = $value;
    }

    /**
     * Send the given notification to the inbox. If no inbox URL is provided, the default inbox URL will be used.
     *
     * @param NotifyPattern $notification The notification object (from the models provided, or a subclass you have made of the NotifyPattern class)
     * @param string|null $inboxUrl The HTTP URI to send the notification to. Omit if using the default inbox_url supplied in the constructor.
     *                              If it is omitted, and no value is passed here then we will also look in the `target.inbox` property of the notification.
     * @param bool $validate Whether to validate the notification before sending. If you are sure the notification is valid, you can set this to false.
     * @return NotifyResponse A NotifyResponse object representing the response from the server.
     * @throws NotifyException
     */
    public function send(NotifyPattern $notification, ?string $inboxUrl = null, bool $validate = true): NotifyResponse
    {
        if ($inboxUrl === null) {
            $inboxUrl = $this->inboxUrl;
        }
        if ($inboxUrl === null) {
            $inboxUrl = $notification->getTarget()->getInbox();
        }
        if ($inboxUrl === null) {
            throw new \ValueError("No inbox URL provided at the client, method, or notification level");
        }

        if ($validate && !$notification->validate()) {
            throw new NotifyException("Attempting to send invalid notification; to override set validate=false when calling this method");
        }

        $resp = $this->http->post($inboxUrl, json_encode($notification->toJsonLd()), [
            "Content-Type" => "application/ld+json;profile=\"https://www.w3.org/ns/activitystreams\""
        ]);

        if ($resp->getStatusCode() === 201) {
            return new NotifyResponse(NotifyResponse::CREATED, $resp->getHeader("Location"));
        } elseif ($resp->getStatusCode() === 202) {
            return new NotifyResponse(NotifyResponse::ACCEPTED);
        }

        throw new NotifyException("Unexpected response: " . $resp->getStatusCode());
    }
}
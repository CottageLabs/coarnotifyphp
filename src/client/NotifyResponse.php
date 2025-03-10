<?php

namespace coarnotify\client;

class NotifyResponse
{
    /**
     * An object representing the response from a COAR Notify inbox.
     *
     * This contains the action that was carried out on the server:
     *
     * - CREATED: a new resource was created
     * - ACCEPTED: the request was accepted, but the resource was not yet created
     *
     * In the event that the resource is created, then there will also be a location
     * URL which will give you access to the resource.
     */
    const CREATED = "created";
    const ACCEPTED = "accepted";

    private $action;
    private $location;

    /**
     * Construct a new NotifyResponse object with the action (created or accepted) and the location URL (optional).
     *
     * @param string $action The action which the server said it took
     * @param string|null $location The HTTP URI for the resource that was created (if present)
     */
    public function __construct(string $action, ?string $location = null)
    {
        $this->action = $action;
        $this->location = $location;
    }

    /**
     * Get the action that was taken, will be one of the constants CREATED or ACCEPTED.
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * Get the HTTP URI of the created resource, if present.
     *
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }
}
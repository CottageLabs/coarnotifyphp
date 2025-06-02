<?php

namespace coarnotify\server;

/**
 * An object representing the response from a COAR Notify server.
 *
 * Server implementations should construct and return this object with the appropriate properties
 * when implementing the :py:meth:`COARNotifyServiceBinding.notification_received` binding
 */
class COARNotifyReceipt
{
    /**
     * The status code for a created resource
     */
    const CREATED = 201;

    /**
     * The status code for an accepted request
     */
    const ACCEPTED = 202;

    /**
     * @var int The status code of the response
     */
    private $status;

    /**
     * @var string|null The HTTP URI of the created resource, if present
     */
    private $location;

    /**
     * Construct a new COARNotifyReceipt object with the status code and location URL (optional)
     *
     * @param int $status The HTTP status code, should be one of the constants `CREATED` (201) or `ACCEPTED` (202)
     * @param string|null $location The HTTP URI for the resource that was created (if present)
     */
    public function __construct(int $status, ?string $location = null)
    {
        $this->status = $status;
        $this->location = $location;
    }

    /**
     * Get the status code of the response. Should be one of the constants `CREATED` (201) or `ACCEPTED` (202)
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * Get the HTTP URI of the created resource, if present
     *
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }
}
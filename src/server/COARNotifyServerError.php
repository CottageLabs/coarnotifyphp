<?php

namespace coarnotify\exceptions;

use Exception;

/**
 * An exception class for server errors in the COAR Notify server implementation.
 *
 * The web layer of your server implementation should be able to intercept this from the
 * `COARNotifyServer->receive` method and return the appropriate HTTP status code and message to the
 * user in its standard way.
 */
class COARNotifyServerError extends Exception
{
    /**
     * @var int HTTP status code for the error
     */
    private $status;

    /**
     * Construct a new COARNotifyServerError with the given status code and message
     *
     * @param int $status HTTP Status code to respond to the client with
     * @param string $message Message to send back to the client
     */
    public function __construct(int $status, string $message)
    {
        $this->status = $status;
        parent::__construct($message);
    }

    /**
     * Get the HTTP status code for the error
     *
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
}
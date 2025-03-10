<?php

namespace coarnotify\exceptions;

use Exception;

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
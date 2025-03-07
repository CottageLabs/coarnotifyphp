<?php

namespace coarnotify\exceptions;

use coarnotify\exceptions\NotifyException;

class ValueError extends NotifyException
{
    public function __construct($message = 'Value error', $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
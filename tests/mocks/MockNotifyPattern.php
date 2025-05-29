<?php

namespace Tests\mocks;

use coarnotify\core\notify\NotifyPattern;
use coarnotify\patterns\accept\Accept;

class MockNotifyPattern extends NotifyPattern
{
    const TYPE = Accept::TYPE;
}
<?php

namespace coarnotify\core\notify;

use coarnotify\core\activitystreams2\Properties;

/**
 * Trait to provide an API for setting and getting the ``summary`` property of a pattern
 */
trait SummaryTrait
{
    /**
     * Get the summary property of the pattern.
     *
     * @return string
     */
    public function getSummary(): string
    {
        return $this->getProperty(Properties::SUMMARY);
    }

    /**
     * Set the summary property of the pattern.
     *
     * @param string $summary
     */
    public function setSummary(string $summary): void
    {
        $this->setProperty(Properties::SUMMARY, $summary);
    }
}
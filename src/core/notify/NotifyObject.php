<?php

namespace coarnotify\core\notify;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;

class NotifyObject extends NotifyPatternPart
{
    public function getCiteAs(): string
    {
        return $this->getProperty(NotifyProperties::CITE_AS);
    }

    public function setCiteAs(string $value)
    {
        $this->setProperty(NotifyProperties::CITE_AS, $value);
    }

    public function getItem()
    {
        $i = $this->getProperty(NotifyProperties::ITEM);
        if ($i !== null) {
            return new NotifyItem($i, false, $this->getValidateProperties(), $this->getValidators(), NotifyProperties::ITEM);
        }
        return null;
    }

    public function setItem($value)
    {
        $this->setProperty(NotifyProperties::ITEM, $value);
    }

    public function getTriple(): array
    {
        $obj = $this->getProperty(Properties::OBJECT_TRIPLE);
        $rel = $this->getProperty(Properties::RELATIONSHIP_TRIPLE);
        $subj = $this->getProperty(Properties::SUBJECT_TRIPLE);
        return [$obj, $rel, $subj];
    }

    public function setTriple(array $value)
    {
        list($obj, $rel, $subj) = $value;
        $this->setProperty(Properties::OBJECT_TRIPLE, $obj);
        $this->setProperty(Properties::RELATIONSHIP_TRIPLE, $rel);
        $this->setProperty(Properties::SUBJECT_TRIPLE, $subj);
    }

    public function validate(): bool
    {
        $ve = new ValidationError();

        $this->requiredAndValidate($ve, Properties::ID, $this->getId());

        if ($ve->hasErrors()) {
            throw $ve;
        }
        return true;
    }
}

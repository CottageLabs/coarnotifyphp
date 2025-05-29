<?php

namespace coarnotify\patterns\announce_relationship;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyObject;

class AnnounceRelationshipObject extends NotifyObject
{
    public function validate(): bool
    {
        $ve = new ValidationError();
        try {
            parent::validate();
        } catch (ValidationError $superve) {
            $ve = $superve;
        }

        $this->requiredAndValidate($ve, Properties::TYPE, $this->getType());

        list($subject, $relationship, $object) = $this->getTriple();
        $this->requiredAndValidate($ve, Properties::SUBJECT_TRIPLE, $subject);
        $this->requiredAndValidate($ve, Properties::RELATIONSHIP_TRIPLE, $relationship);
        $this->requiredAndValidate($ve, Properties::OBJECT_TRIPLE, $object);

        if ($ve->hasErrors()) {
            throw $ve;
        }

        return true;
    }
}
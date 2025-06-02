<?php

namespace coarnotify\patterns\announce_relationship;

use coarnotify\core\activitystreams2\Properties;
use coarnotify\exceptions\ValidationError;
use coarnotify\core\notify\NotifyObject;

/**
 * Custom object class for Announce Relationship to apply the custom validation
 */
class AnnounceRelationshipObject extends NotifyObject
{
    /**
     * Extend the base validation to include the following constraints:
     *
     * * The object triple is required and each part must validate
     *
     * @return bool
     * @throws ValidationError
     * @throws \coarnotify\exceptions\ValueError
     */
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
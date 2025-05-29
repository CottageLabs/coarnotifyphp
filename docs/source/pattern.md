# Implementing a Custom Pattern

For many implementations, you can use the default pattern objects supplied by this library. If your notifications 
have additional requirements, such as service-specific validation rules or additional required/optional fields, 
you can create your own pattern classes by subclassing the base pattern classes.

## Adding a Simple Field

Suppose we want to add a field to an `AnnounceEndorsement` pattern to indicate a "time to live" (TTL) for the 
endorsement. This could represent the number of days for which the endorsement record is guaranteed to be available at 
the given identifier.

We would extend the `AnnounceEndorsement` class like this:

```php
<?php

namespace coarnotify\patterns\announce_endorsement;

class AnnounceEndorsementWithTTL extends AnnounceEndorsement
{
    public function getTtl(): ?int
    {
        return $this->getProperty('ttl');
    }

    public function setTtl(int $value): void
    {
        $this->setProperty('ttl', $value);
    }
}
```

Now any `AnnounceEndorsement` notification containing a `ttl` field can be read and written using this object.

## Extending the Validation

To validate the `ttl` field to ensure it contains a positive integer, we can hard-code the validation:

```php
<?php

namespace coarnotify\patterns\announce_endorsement;

use coarnotify\exceptions\ValidationError;

class AnnounceEndorsementWithTTL extends AnnounceEndorsement
{
    public function getTtl(): ?int
    {
        return $this->getProperty('ttl');
    }

    public function setTtl(int $value): void
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('ttl must be a positive integer');
        }
        $this->setProperty('ttl', $value);
    }

    public function validate(): bool
    {
        $ve = new ValidationError();

        try {
            parent::validate();
        } catch (ValidationError $superve) {
            $ve = $superve;
        }

        if ($this->getTtl() < 0) {
            $ve->addError('ttl', 'ttl must be a positive integer');
        }

        if ($ve->hasErrors()) {
            throw $ve;
        }

        return true;
    }
}
```

Alternatively, you can create a custom validator and add it to the validation ruleset:

```php
<?php

namespace coarnotify\patterns;

use coarnotify\validate\Validator;
use coarnotify\exceptions\ValidationError;

class AnnounceEndorsementWithTTL extends AnnounceEndorsement
{
    private static function positiveInteger($value): bool
    {
        if (is_int($value) && $value > 0) {
            return true;
        }
        throw new \InvalidArgumentException('Value must be a positive integer');
    }

    public function __construct(array $properties = [], Validator $validator = null)
    {
        $rules = $validator ? $validator->getRules() : [];
        $rules['ttl'] = ['default' => [self::class, 'positiveInteger']];
        $customValidator = new Validator($rules);

        parent::__construct($properties, $customValidator);
    }

    public function getTtl(): ?int
    {
        return $this->getProperty('ttl');
    }

    public function setTtl(int $value): void
    {
        $this->setProperty('ttl', $value);
    }

    public function validate(): bool
    {
        $ve = new ValidationError();

        try {
            parent::validate();
        } catch (ValidationError $superve) {
            $ve = $superve;
        }

        $this->requiredAndValidate($ve, 'ttl', $this->getTtl());

        if ($ve->hasErrors()) {
            throw $ve;
        }

        return true;
    }
}
```

## Adding a Complex/Nested Field

To customize fields nested in one of the pattern parts, override the pattern part with a custom implementation and 
wire it to the appropriate accessor on the pattern object.

For example, to add a custom `object` to the `AnnounceEndorsement` pattern:

```php

namespace coarnotify\patterns\announce_endorsement;

use coarnotify\core\notify\NotifyObject;
use coarnotify\core\notify\NotifyProperties;

class AnnounceEndorsementObject extends NotifyObject
{
    public function getCustomField(): ?string
    {
        return $this->getProperty('custom_field');
    }

    public function setCustomField(string $value): void
    {
        $this->setProperty('custom_field', $value);
    }
}

class AnnounceEndorsementWithCustomObject extends AnnounceEndorsement
{
    public function getObject(): ?AnnounceEndorsementObject
    {
        $obj = $this->getProperty(NotifyProperties::OBJECT);
        if ($obj !== null) {
            return new AnnounceEndorsementObject($obj, false, $this->validateProperties, $this->validators, NotifyProperties::OBJECT);
        }
        return null;
    }
}
```

Now, when accessing the `object` property on an `AnnounceEndorsementWithCustomObject` instance, you get an instance 
of `AnnounceEndorsementObject`.
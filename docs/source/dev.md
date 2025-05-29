# Information for Developers

## Compiling the documentation

To build the documentation, run the following command:

    doxygen doxygen.cfg


## Adding new patterns

1. Create a new module for the model in ``src/patterns`` (for example ``src/patterns/announce_endorsement``)
2. Create the new model files/classes in the new module (for example, ``AnnounceIngest.php``) and implement as needed
3. Review the validation requirements of the new model and ensure validation is updated
4. Add the new model to the factory list of models in ``src/factory/COARNotifyFactory.MODELS``
5. Create a fixture and fixture factory in ``src/tests/fixtures`` (for example, ``src/test/fixtures/AnnounceIngest.php``)
6. Add a unit test for the new model in ``src/tests/unit/TestModels.php``, and confirm it works
7. Add a unit test for the model factory in ``src/tests/unit/TestFactory.php``, and confirm it works
8. Add an integration test for the new model in ``src/tests/integration/TestClient.php``, and confirm it works
9. Add validation tests for the new model in ``src/tests/unit/TestValidate.php``, and confirm they work

## Testing

### Unit

Unit tests are located in ``src/tests/unit`` and can be run with the following command (or your preferred test runner):

```
phpunit src/tests/unit
```

### Integration

Integration tests require a notify inbox to be available

This can be done by starting the test inbox server.  To do this you will first need to configure your local settings for the server.

Default configuration is in ``src/tests/server/settings.php`` and can be overridden by providing your own settings
in a file called ``local.php`` in the same directory.

Then start the server with the following command:

```
php -S localhost:8080 -t tests/server
```

Integration tests are located in ``src/tests/integration`` and can be run with the following command (or your preferred test runner):

```
phpunit src/tests/integration
```

## Making a release


1. Update the version number in ``composer.json``

2. Make the release in github, with the version number as the tag

TODO
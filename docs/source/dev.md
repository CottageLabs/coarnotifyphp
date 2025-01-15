Information for Developers
==========================

Compiling the documentation
---------------------------

To build the documentation, run the following command:

    doxygen doxygen.cfg


Adding new patterns
-------------------

1. Create a new module for the model in ``coarnotify.models`` (for example ``coarnotify.models.announce_ingest``)
2. Create the new model class in the new module (for example, ``AnnounceIngest``) and implement as needed
3. Review the validation requirements of the new model and ensure validation is updated (update the model spreadsheet)
4. Add the new model to ``coarnotify.models.__init__.py`` so it can be imported from ``coarnotify.models``
5. Add the new model to the factory list of models in ``coarnotify.factory.COARNotifyFactory.MODELS``
6. Create a fixture and fixture factory in ``coarnotify.test.fixtures`` (for example, ``coarnotify.test.fixtures.announce_ingest``)
7. Import the new fixture in ``coarnotify.test.fixtures.__init__.py``
8. Add a unit test for the new model in ``coarnotify.test.unit.test_models``, and confirm it works
9. Add a unit test for the model factory in ``coarnotify.test.unit.test_factory``, and confirm it works
10. Add an integration test for the new model in ``coarnotify.test.integration.test_client``, and confirm it works
11. Add validation tests for the new model in ``coarnotify.test.unit.test_validate``, and confirm they work

Testing
-------

Unit
^^^^

Unit tests are located in ``coarnotify.test.unit`` and can be run with the following command (or your preferred test runner):

.. code-block:: console

    pytest coarnotify/test/unit

Integration
^^^^^^^^^^^

Integration tests require a notify inbox to be available

This can be done by starting the test inbox server.  To do this you will first need to configure your local settings for the server.

Default configuration is in ``coarnotify/test/server/settings.py`` and can be overridden by providing your own settings file as an environment variable ``COARNOTIFY_SETTINGS``.

The main things you may wish to override are:

* STORE_DIR: the directory to store the notifications.  You MUST supply your own path
* PORT: the port to run the server on.  Default is 5005

Create a local config file called something like ``local.cfg`` containing those properties

.. code-block:: python

    STORE_DIR = '/path/to/store/notifications'
    PORT = 5005


Then start the server with the following command:

.. code-block:: console

    COARNOTIFY_SETTINGS=local.cfg; python coarnotify/test/server/inbox.py

Integration tests are located in ``coarnotify/test/integration`` and can be run with the following command (or your preferred test runner):

.. code-block:: console

    pytest coarnotify/test/integration

Making a release
----------------

1. Update the version number in ``setup.py`` and ``coarnotify/__init__.py``

2. Make the release in github, with the version number as the tag

3. Build the package locally:

.. code-block:: console

    python -m pip install build twine
    python -m build
    twine check dist/*

4. Test upload the package to TestPypi (you will need an account on https://test.pypi.org and to set up an API token):

.. code-block:: console

    twine upload -r testpypi dist/*

5. Do the release to the real Pypi:

.. code-block:: console

    twine upload dist/*
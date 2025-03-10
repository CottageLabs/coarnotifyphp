# Integration tests

In order to run the tests in this suite, you will need to have a working inbox to run against, which should
respond to notify requests with a 201 (CREATED) status code and a location header.

The inbox should be available at http://localhost:8080/inbox

Otherwise, you will need to modify the files to specify the inbox you want to test against.

You may use the test inbox, which can be started with the following command:

```bash
php -S localhost:8080 -t tests/server
```
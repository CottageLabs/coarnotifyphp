# COAR Notify PHP Bindings

https://coar-notify.net/

The COAR Notify Protocol is a set of profiles, constraints and conventions around the use of W3C Linked Data Notifications (LDN) to integrate repository systems with relevant services in a distributed, resilient and web-native architecture.

This library provides a PHP implementation of the COAR Notify Protocol version 1.0.1 (https://coar-notify.net/specification/1.0.1/),
which covers the following aspects of the protocol:

* A set of model objects which can be used to represent the core Patterns
* Built-in validation for Patterns and over values passed to the model APIs
* Parse and serialise to JSON-LD
* Send notifications to a target inbox
* Provides a simple server-side binding to easily integrate notifications into your application

In addition, it provides:

* A comprehensive test suite demonstrating the use of the library
* A simple inbox which can be run locally for testing
* A simple set of integration tests for sending notifications to an inbox
* A customisable HTTP layer to allow you to use your own, or to build in custom authentication routines
* Detailed API documentation

Full documentation here: https://cottagelabs.github.io/coarnotifyphp/build/html/index.html
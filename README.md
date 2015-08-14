# skeleton-error

## Description

This library takes care of the error reporting. If debug is enabled, it
shows the error in a pretty layout.
If debug is disabled, errors are mailed to the configured email address.

## Installation

Installation via composer:

    composer require tigron/skeleton-error

## Howto

Initialize the package

    Skeleton\Error\Config::$debug = true // Yes I want to show the errors
    Skeleton\Error\Config::$errors_from = $application_email_address
    Skeleton\Error\Config::$errors_to = $developer_email_address

Now make it the default error handler

    set_error_handler(['\Skeleton\Error\Handler', 'error']);
    set_exception_handler(['\Skeleton\Error\Handler', 'exception']);

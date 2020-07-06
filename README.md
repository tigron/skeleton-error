# skeleton-error

## Description

This library takes care of the error reporting.

It will use Whoops and Sentry if their presence can be detected and the
required configuration exists, otherwise it will fall back to its own basic
error handler.

It can spam your mailbox with errors if you so desire.

## Installation

Installation via composer:

    composer require tigron/skeleton-error

## Howto

Initialize the package

    \Skeleton\Error\Config::$debug = true; // Yes I want to show the errors
    \Skeleton\Error\Config::$sentry_dsn = 'http://foo:bar@sentry.example.com/123'; // Your Sentry DSN (optional)
    \Skeleton\Error\Config::$mail_errors_to = 'colleague@example.com';
    \Skeleton\Error\Config::$mail_errors_from = 'errors@example.com';

Now make it the default error handler

    \Skeleton\Error\Handler::enable();

If you install the composer packages `filp/whoops`, `sentry\sdk` and/or
`sentry/sentry`, the handler wil use them.

# skeleton-error

## Description

This library contains the error handling logic of `skeleton`.

It will use `filp/whoops` and `sentry/sentry` if their presence can be detected
and the required configuration exists. If not, it will fall back to its own
basic error handler.

The basic error handlers can print debug information, as well as send e-mail to
a configured address.

## Installation

Installation via composer:

    composer require tigron/skeleton-error

## Configuration

If you want to enable `skeleton-error` for your application, you need to call
the `Handler::enable()` method, which will register the error handlers:

    \Skeleton\Error\Handler::enable();

Setting the `debug` flag to true will result in detailed error messages being
displayed in the browser, using the basic built-in handler or `filp/whoops`,
depending on what is available:

    \Skeleton\Error\Config::$debug = true;

You can provide additional information to the error handler, such as the release
version and the environment the error occurred in. This can help with filtering
reports. This is currently only supported by the `sentry` handler:

    \Skeleton\Error\Config::$environment = 'development';
    \Skeleton\Error\Config::$release = 'project@1.0.0';

### E-mail handler

If you would like to receive error reports via e-mail, set the `mail_errors_to`
and `mail_errors_from` options to the relevant addresses. You will also need to
make sure that the system your application is running on can send mail:

    \Skeleton\Error\Config::$mail_errors_to = 'colleague@example.com';
    \Skeleton\Error\Config::$mail_errors_from = 'errors@example.com';

### Sentry handler

If you would like to use [Sentry](https://github.com/getsentry/sentry-php), you
need to install `sentry/sdk` (recommended) or `sentry/sentry` (deprecated).

Once installed, configure the DSN which it will use:

    \Skeleton\Error\Config::$sentry_dsn = 'http://foo:bar@sentry.example.com/1';

### Whoops handler

If you would like to use [Whoops](https://github.com/filp/whoops), you need to
install `filp/whoops`. No further configuration is required.

## Events

### Error context

#### sentry_before_send

The `sentry_before_send` can have two different signatures, depending on the
version of Sentry you have installed.

For `sentry\sdk` (which depends on `sentry/sentry` version 2 or higher), the
event will be called as the `beforeSend` callback. In the example below, we use
`$event->getUserContext()` for version 2, whereas version 3 should use
`$event->getUser()`. More information is available
[here](https://docs.sentry.io/platforms/php/configuration/filtering/#using-beforesend)

    public function sentry_before_send(\Sentry\Event $event) {
        $event->getUserContext()->setUsername('john-doe');
        return $event;
    }

For the deprecated `sentry\sentry` version 1 and below, the event will be called
as the `send_callback` function. More information is available
[here](https://docs.sentry.io/clients/php/config/#available-settings)

    public function sentry_before_send(&$data) {
        $data['user']['username'] = 'john-doe';
    }

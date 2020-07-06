<?php
/**
 * sentry/sdk handler for for Skeleton\Error
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error\Handler;

class SentrySdk extends Handler {
	/**
	 * Handle an error with Sentry
	 *
	 * @return string
	 */
	public function handle() {
		// Instantiate a new Raven_Client with the configured DSN
        $builder = \Sentry\ClientBuilder::create(['dsn' => \Skeleton\Error\Config::$sentry_dsn]);
		\Sentry\State\Hub::getCurrent()->bindClient($builder->getClient());

		// Assign the session to the extra context
		if (isset($_SESSION)) {
			\Sentry\State\Hub::getCurrent()->configureScope(function (\Sentry\State\Scope $scope): void {
				$scope->setExtra('session', print_r($_SESSION, true));
			});
		}

		\Sentry\SentrySdk::getCurrentHub()->captureException($this->exception);
	}

	/**
	 * Can this handler run?
	 *
	 * @return bool
	 */
	public function can_run() {
		// Sentry should always be enabled
		return true;
	}
}

<?php
/**
 * Sentry handler for for Skeleton\Error
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error\Handler;

class Sentry extends Handler {
	/**
	 * Handle an error with Sentry
	 *
	 * @return string
	 */
	public function handle() {
		// Instantiate a new Raven_Client with the configured DSN
		$client = new \Raven_Client(\Skeleton\Error\Config::$sentry_dsn);

		// Assign the session to the extra context
		if (isset($_SESSION)) {
			$client->extra_context(['session' => print_r($_SESSION, true)]);
		}

		$client->captureException($this->exception);
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
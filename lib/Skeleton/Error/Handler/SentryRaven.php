<?php
/**
 * sentry/sentry handler for for Skeleton\Error
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Error\Handler;

class SentryRaven extends Handler {

	/**
	 * Handle when reporting exception manually
	 *
	 * @var bool $reports
	 */
	protected $reports = true;

	/**
	 * Handle an error with Sentry
	 *
	 * @return string
	 */
	public function handle() {
		// Instantiate a new Raven_Client with the configured DSN
		$client = new \Raven_Client(\Skeleton\Error\Config::$sentry_dsn);

		if (class_exists('\Skeleton\Core\Application')) {
			try {
				$application = \Skeleton\Core\Application::get();
			} catch (\Exception $e) {
				$application = null;
			}

			if ($application !== null && $application->event_exists('error', 'sentry_before_send')) {
				$client->setSendCallback($application->get_event_callable('error', 'sentry_before_send'));
			}
		}

		if (\Skeleton\Error\Config::$environment !== null) {
			$client->setEnvironment(\Skeleton\Error\Config::$environment);
		}

		if (\Skeleton\Error\Config::$release !== null) {
			$client->setRelease(\Skeleton\Error\Config::$release);
		}

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

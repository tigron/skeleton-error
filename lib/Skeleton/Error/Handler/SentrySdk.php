<?php
/**
 * sentry/sdk handler for for Skeleton\Error
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Error\Handler;

class SentrySdk extends Handler {

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
		// Start building the options array by supplying the configured DSN
		$options = ['dsn' => \Skeleton\Error\Config::$sentry_dsn];

		if (class_exists('\Skeleton\Core\Application')) {
			try {
				$application = \Skeleton\Core\Application::get();
			} catch (\Exception $e) {
				$application = null;
			}

			if ($application !== null && $application->event_exists('error', 'sentry_before_send')) {
				$options['before_send'] = \Closure::fromCallable($application->get_event_callable('error', 'sentry_before_send'));
			}
		}

		if (\Skeleton\Error\Config::$environment !== null) {
			$options['environment'] = \Skeleton\Error\Config::$environment;
		}

		if (\Skeleton\Error\Config::$release !== null) {
			$options['release'] = \Skeleton\Error\Config::$release;
		}

		$builder = \Sentry\ClientBuilder::create($options);
		\Sentry\SentrySdk::getCurrentHub()->bindClient($builder->getClient());

		// Assign the session to the extra context
		$environment = [];
		if (isset($_SESSION)) {
			$environment['session'] = $_SESSION;
		}
		if (!empty($_POST)) {
			$environment['post'] = $_POST;
		}
		if (!empty($_GET)) {
			$environment['get'] = $_GET;
		}
		if (isset($_SERVER)) {
			$environment['server'] = $_SERVER;
		}
		if (count($environment) > 0) {
			\Sentry\SentrySdk::getCurrentHub()->configureScope(
				function (\Sentry\State\Scope $scope) use ($environment): void {
					$scope->setContext('environment', $environment);
					if (isset($_SERVER['REMOTE_ADDR'])) {
						$scope->setUser(['ip_address' => $_SERVER['REMOTE_ADDR'] ]);
					}
				}
			);
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

<?php
/**
 * Application handler for for Skeleton\Error
 *
 * The application handler basically makes sure the user's application receives
 * a callback, so they can display a pretty error instead of the default.
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error\Handler;

class Application extends Handler {

	/**
	 * Handle the exception with an event in the current application
	 *
	 * @return string
	 */
	public function handle() {
		$application = \Skeleton\Core\Application::get();

		if ($application->event_exists('error', 'exception')) {
			// If the error is handled within the application, no need to continue
			// internally.

			$proceed = $application->call_event('error', 'exception', [ $this->exception ]);
			if ($proceed === true) {
				$this->last_handler = false;
			} else {
				$this->last_handler = true;			
			}
		}
	}

	/**
	 * Can this handler run?
	 *
 	 * @return bool
	 */
	public function can_run() {
		// This handler can only run when we have an application defined
		if (class_exists('\\Skeleton\\Core\\Application') === false) {
			return false;
		}

		try {
			\Skeleton\Core\Application::get();
			return true;
		} catch (\Exception $e) {};

		return false;
	}
}

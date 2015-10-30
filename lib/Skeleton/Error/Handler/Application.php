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
	 * Handle the exception with a hook in the current application
	 *
	 * @return string
	 */
	public function handle() {
		if (\Skeleton\Core\Hook::exists('handle_error', [$this->exception])) {
			// If the error is handled within the application, no need to continue
			// internally.
			$this->last_handler = true;
			\Skeleton\Core\Hook::call('handle_error', [$this->exception]);
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

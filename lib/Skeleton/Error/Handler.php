<?php
/**
 * Error handler for Skeleton
 *
 * If Whoops is installed, it will use that, otherwise it will fall back to the
 * (basic) internal error handler for CLI and web.
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Error;

use Exception;

class Handler {

	/**
	 * Handler singleton
	 *
	 * @var array
	 */
	private static ?self $handler = null;

	/**
	 * All handlers we need to execute
	 *
	 * @var array
	 */
	private $handlers = [];

	/**
	 * Patterns for paths we shouldn't care about
	 *
	 * @var array
	 */
	private $silenced_paths = [];

	/**
	 * Are we registered yet?
	 *
	 * @var bool
	 */
	private $is_registered = false;

	/**
	 * Enable the error handler, assuming defaults
	 */
	public static function enable(): self {
		error_reporting(Config::$error_reporting);

		$handler = new self();
		$handler->register();

		self::$handler = $handler;

		return self::$handler;
	}

	/**
	 * Get the current hanlder
	 */
	public static function get(): self {
		if (self::$handler === null) {
			throw new \Exception('Error handling not enabled');
		}

		return self::$handler;
	}

	/**
	 * Register ourselves
	 */
	public function register() {
		if ($this->is_registered === true) {
			return;
		}

		// Automatically use sentry/sentry if detected
		if ($this->detected_sentry_raven() === true && Config::$sentry_dsn !== null) {
			$this->add_handler(new Handler\SentryRaven());
		}

		// Automatically use sentry/sdk if detected
		if ($this->detected_sentry_sdk() === true && Config::$sentry_dsn !== null) {
			$this->add_handler(new Handler\SentrySdk());
		}

		// If we configured mail, send mail too
		if (Config::$mail_errors_to !== null) {
			$this->add_handler(new Handler\Mail());
		}

		// Always add the application handler
		$this->add_handler(new Handler\Application());

		if (Config::$debug === true) {
			// If we detect Whoops, use that instead of our basic handler
			if ($this->detected_whoops()) {
				$this->add_handler(new Handler\Whoops());
			} else {
				$this->add_handler(new Handler\BasicCLI());
				$this->add_handler(new Handler\Basic());
			}
		} else {
			// If we are not in debug, and we end up as the last handler,
			// send some crude error message to the user's browser.
			$this->add_handler(new Handler\BasicOutput());
		}

		register_shutdown_function([$this, 'handle_shutdown']);
		set_error_handler([$this, 'handle_error']);
		set_exception_handler([$this, 'handle_exception']);
		

		$this->is_registered = true;
	}

	/**
	 * Unregister ourselves
	 */
	public function unregister() {
		restore_exception_handler();
		restore_error_handler();
	}

	/**
	 * Add a handler
	 *
	 * @param Skeleton\Error\Handler\Interface $handler
	 */
	public function add_handler(Handler\HandlerInterface $handler) {
		$this->handlers[] = $handler;
	}

	/**
	 * Add a pattern for a path to silence
	 *
	 * @param string $pattern A pattern compatible with preg_match()
	 */
	public function add_silenced_path($pattern) {
		$this->silenced_paths[] = $pattern;
	}


	/**
	 * Handle an exception
	 *
	 * @param $exception (can be \Throwable or \Exception)
	 * @return string $output Output generated by the configured handlers
	 */
	public function handle_exception($exception) {
		$quit = false;
		$output = '';

		foreach ($this->handlers as $handler) {
			if ($handler->can_run() === false) {
				continue;
			}

			$handler->set_exception($exception);

			// The output is a concatenation of all output returned by the
			// handlers. Ideally, we only have one handler sending output.
			$output .= $handler->handle();

			if ($handler->requests_quit() === true) {
				$quit = $handler->requests_quit();
			}

			// If the handler claims it should be the last handler, break out of the loop
			if ($handler->is_last() === true) {
				break;
			}
		}

		if (trim($output) !== '') {
			echo $output;
		}

		if ($quit === true) {
			// Since we are exiting after an error, don't exit with 0
			exit(1);
		}
	}

	/**
	 * Handle an error
	 *
	 * This method is complatible with the error_handler callback, as documented
	 * for set_error_handler()
	 *
	 * @param int $level The error level
	 * @param string $message The error message
	 * @param string $file The file in which the error occurred
	 * @param int $errline The line number in the file where the error occurred
	 * @return bool
	 */
	public function handle_error($level, $message, $file = null, $line = null) {
		if ($level & error_reporting()) {
			if ($this->is_silenced($file)) {
				return true;
			}

			throw new \ErrorException($message, 0, $level, $file, $line);
		}

		// Propagate error to the next handler, allows error_get_last() to
		// work on silenced errors.
		return false;
	}

	/**
	 * Handle the shutdown event
	 */
	public function handle_shutdown() {
		// Since we can not unregister a shutdown function, simply don't do
		// anything if we aren't registered
		if ($this->is_registered === false) {
			return;
		}
	}

	/**
	 * Report exception manually
	 *
	 * @access public
	 * @param $exception (can be \Throwable or \Exception)
	 */
	public function report_exception($exception) {
		if ($this->is_registered === false) {
			$this->register();
		}

		foreach ($this->handlers as $handler) {
			if ($handler->allow_report() === false) {
				continue;
			}

			$handler->set_exception($exception);
			$handler->handle();
		}
	}

	/**
	 * Check if we have detected a Whoops installation
	 *
	 * @return bool
	 */
	private function detected_whoops() {
		return class_exists('Whoops\Run');
	}

	/**
	 * Check if we have detected a sentry/sentry package
	 *
	 * @return bool
	 */
	private function detected_sentry_raven() {
		return class_exists('Raven_Client');
	}

	/**
	 * Check if we have detected a sentry/sdk package
	 *
	 * @return bool
	 */
	private function detected_sentry_sdk() {
		return class_exists('\Sentry\SentrySdk');
	}

	/**
	 * Check if a given path has been silenced
	 *
	 * @param string $path
	 * @return bool
	 */
	private function is_silenced($path) {
		foreach ($this->silenced_paths as $silenced_path) {
			if (preg_match($silenced_path, $path) === false) {
				continue;
			}

			return true;
		}

		return false;
	}
}

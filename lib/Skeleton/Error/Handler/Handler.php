<?php
/**
 * Basic handler for for Skeleton\Error
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error\Handler;

abstract class Handler implements HandlerInterface {
	/**
	 * Exception to handle
	 *
	 * @var \Exception $exception
	 */
	protected $exception = null;

	/**
	 * Quit after handling?
	 *
	 * @var bool $quit
	 */
	protected $quit = false;

	/**
	 * Run other handlers after this one?
	 *
	 * @var bool $last_handler
	 */
	protected $last_handler = false;

	/**
	 * Handle when reporting exception manually
	 *
	 * @var bool $reports
	 */
	protected $reports = false;

	/**
	 * Set the exception to handle
	 *
	 * @param $exception (can be \Throwable or \Exception)
	 */
	public function set_exception($exception) {
		$this->exception = $exception;
	}

	/**
	 * Is this handler able to run?
	 *
	 * This method can return a different answer depending on the context, one
	 * example that comes to mind is a dedicated handler for CLI processes.
	 *
	 * @return bool
	 */
	public function can_run() {
		return false;
	}

	/**
	 * Does this handler request to quit after doing it's thing?
	 *
	 * @return bool
	 */
	public function requests_quit() {
		return $this->quit;
	}

	/**
	 * Does this handler request to be the last one?
	 *
	 * @return bool
	 */
	public function is_last() {
		return $this->last_handler;
	}

	public function allow_report() {
		return $this->reports;
	}
}

<?php
/**
 * Basic user output handler for for Skeleton\Error
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error\Handler;

class BasicOutput extends Handler {

	/**
	 * This is always the last handler
	 *
	 * @var bool $last_handler
	 */
	protected $last_handler = true;

	/**
	 * Always quit after handling, this is the non-debug handler so every error
	 * is fatal.
	 *
	 * @var bool $quit
	 */
	protected $quit = true;

	/**
	 * Handle an error with basic user output
	 *
	 * @return string
	 */
	public function handle() {
		return 'An unexpected error has occurred. Please try again later.';
	}

	/**
	 * Can this handler run?
	 *
	 * @return bool
	 */
	public function can_run() {
		// This handler can always run
		return true;
	}
}

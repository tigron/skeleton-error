<?php
/**
 * Whoops handler for for Skeleton\Error
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error\Handler;

class Whoops extends Handler {

	/**
	 * Always quit after Whoops, other output is unreadable anyway
	 *
	 * @var bool
	 */
	protected $quit = true;

	/**
	 * Handle an error with Whoops
	 *
	 * @return string
	 */
	public function handle() {
		$whoops = new \Whoops\Run();
		$whoops->pushHandler(new \Whoops\Handler\PlainTextHandler());
		$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
		$whoops->allowQuit(false);
		$whoops->writeToOutput(false);
		return $whoops->handleException($this->exception);
	}

	/**
	 * Can this handler run?
	 *
	 * @return bool
	 */
	public function can_run() {
		// The Whoops handler handles things internally anyway
		return true;
	}
}

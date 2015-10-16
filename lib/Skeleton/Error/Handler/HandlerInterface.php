<?php
/**
 * Basic handler for for Skeleton\Error
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error\Handler;

interface HandlerInterface {
	/**
	 * @return string $output A handler will always reply with its output
	 */
	public function handle();

	/**
	 * @param \Exception $exception
	 */
	public function set_exception(\Exception $exception);

	/**
	 * @return bool
	 */
	public function can_run();
}

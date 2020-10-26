<?php
/**
 * Basic CLI handler for for Skeleton\Error
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error\Handler;

class BasicCLI extends Handler {

	/**
	 * Handle an error with the most basic handler on the CLI
	 *
	 * @return string
	 */
	public function handle() {
		// If this is a reqular exception, always quit, else check the type
		if (!($this->exception instanceof \ErrorException)) {
			$this->quit = true;
		} else {
			// Only quit after specific error types
			switch ($this->exception->getSeverity()) {
				case E_ERROR:
				case E_CORE_ERROR:
				case E_USER_ERROR:
					$this->quit = true;
			}
		}

		if ($this->exception instanceof \ErrorException) {
			$error_type = \Skeleton\Error\Util\Misc::translate_error_code($this->exception->getSeverity());
		} else {
			$error_type = 'Exception';
		}

		$output = "\n";
		$output .= 'Error: ' . $this->exception->getMessage() . "\n";
		$output .= 'Type: ' . $error_type . "\n";
		$output .= 'File: ' . $this->exception->getFile() . "\n";
		$output .= 'Line: ' . $this->exception->getLine() . "\n";
		$output .= 'Time: ' . date('Y-m-d H:i:s') . "\n";

		if (!($this->exception instanceof \ErrorException)) {
			ob_start();
			debug_print_backtrace();
			$backtrace = ob_get_clean();

			$output .= "\n";
			$output .= $backtrace . "\n";
		}

		return $output;
	}

	/**
	 * Can this handler run?
	 *
	 * @return bool
	 */
	public function can_run() {
		// This handler can only run in CLI
		if (PHP_SAPI === 'cli') {
			return true;
		}

		return false;
	}
}

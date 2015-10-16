<?php
/**
 * Basic handler for for Skeleton\Error
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error\Handler;

class Basic extends Handler {
	/**
	 * This is always the last handler
	 *
	 * @var bool $last_handler
	 */
	protected $last_handler = true;

	/**
	 * Handle an error with the most basic handler
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

		return self::get_html($this->exception);
	}

	/**
	 * Can this handler run?
	 *
	 * @return bool
	 */
	public function can_run() {
		// This handler can not run in CLI
		if (PHP_SAPI === 'cli') {
			return false;
		}

		return true;
	}

	/**
	 * Produce a subject based on the error
	 *
	 * @param \Exception $exception
	 * @return string
	 */
	public static function get_subject(\Exception $exception) {
		$application = null;

		try {
			$application = \Skeleton\Core\Application::get();
		} catch (\Exception $e) {}

		if ($application === null) {
			$hostname = 'unknown';
			$name = 'unknown';
		} else {
			$hostname = $application->hostname;
			$name = $application->name;
		}

		if ($exception instanceof \ErrorException) {
			$subject = get_class($exception) . ' (' . \Skeleton\Error\Util\Misc::translate_error_code($exception->getSeverity()) . ') on ' . $hostname . ' (' . $name . ')';
		} else {
			$subject = get_class($exception) . ' on ' . $hostname . ' (' . $name . ')';
		}

		return $subject;
	}

	/**
	 * Produce some HTML around the error
	 *
	 * @param \Exception $exception
	 * @return string
	 */
	public static function get_html(\Exception $exception) {
		$subject = self::get_subject($exception);

		if ($exception instanceof \ErrorException) {
			$error_type = \Skeleton\Error\Util\Misc::translate_error_code($exception->getSeverity());
		} else {
			$error_type = 'Exception';
		}

		$error_info = '';
		$error_info .= 'Error: ' . $exception->getMessage() . "\n";
		$error_info .= 'Type: ' . $error_type . "\n";
		$error_info .= 'File: ' . $exception->getFile() . "\n";
		$error_info .= 'Line: ' . $exception->getLine() . "\n\n";
		$error_info .= 'Time: ' . date('Y-m-d H:i:s') . "\n";

		$html =
		'<html>' .
		'   <head>' .
		'       <title>' . $subject . '</title>' .
		'       <style type="text/css">' .
		'           body { font-family: sans-serif; background: #eee; } ' .
		'           pre { border: 1px solid #1b2582; background: #ccc; padding: 5px; }' .
		'           h1 { width: 100%; background: #183452; font-weight: bold; color: #fff; padding: 2px; font-size: 16px;} ' .
		'           h2 { font-size: 15px; } ' .
		'       </style>' .
		'   </head>' .
		'   <body>' .
		'   <h1>' . $subject . '</h1>';

		$html .= '<h2>Message</h2> <pre>' . $exception->getMessage() . '</pre>';

		$html .= '<h2>Info</h2> <pre>' . $error_info . '</pre>';

		// Backtraces are not very useful for anything else but real exceptions
		if (!($exception instanceof \ErrorException)) {
			ob_start();
				debug_print_backtrace();
				$backtrace = ob_get_contents();
			ob_end_clean();

			$html .= '<h2>Backtrace</h2> <pre>' . $backtrace . '</pre>';
		}

		$vartrace = [
			'_GET'      => isset($_GET) ? $_GET : null,
			'_POST'     => isset($_POST) ? $_POST : null,
			'_COOKIE'   => isset($_COOKIE) ? $_COOKIE : null,
			'_SESSION'  => isset($_SESSION) ? $_SESSION : null,
			'_SERVER'   => isset($_SERVER) ? $_SERVER : null
		];

		$html .= '<h2>Vartrace</h2> <pre> ' . print_r($vartrace, true) . '</pre>';

		$html .=
		'   </body>' .
		'</html>';

		return $html;
	}
}

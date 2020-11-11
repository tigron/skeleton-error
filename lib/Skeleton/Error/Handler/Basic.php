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
	 * @param $exception (can be \Throwable or \Exception)
	 * @return string
	 */
	public static function get_subject($exception) {
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
	 * @param $exception (can be \Throwable or \Exception)
	 * @return string
	 */
	public static function get_html($exception) {
		$subject = self::get_subject($exception);

		if ($exception instanceof \ErrorException) {
			$error_type = \Skeleton\Error\Util\Misc::translate_error_code($exception->getSeverity());
		} else {
			$error_type = 'Exception';
		}

		$error_info = '<table><tr><td width="20%" valign="top">Error</td><td>' . $exception->getMessage() . '</td></tr>';
		$error_info .= '<tr><td valign="top">Type</td><td>' . $error_type . '</td></tr>';
		$error_info .= '<tr><td valign="top">File</td><td> ' . $exception->getFile() . ' <span style="color: #666">at</span> line ' . $exception->getLine() . '</td></tr>';
		$error_info .= '<tr><td valign="top">Time</td><td>' . date('Y-m-d H:i:s') . '</td></tr></table>';

		$html =
		'<html>' .
		'   <head>' .
		'       <title>' . $subject . '</title>' .
		'       <style type="text/css">' .
		'           body, td { font-family: Verdana, Arial, sans-serif; font-size: 9pt } ' .
		'           p { border: 1px solid #999; background: #fcfcfc; padding: 20px; font-size: 9pt }' .
		'           h1 { width: 100%; font-weight: bold; color: #333; padding: 2px; font-size: 20pt;} ' .
		'           h2 { font-size: 14pt; } ' .
		'			pre { max-width: 650px; text-overflow: ellipsis; overflow-wrap: break-word; white-space: pre-wrap } ' .
		'			hr { border: solid 1px #ddd }' .
		'       </style>' .
		'   </head>' .
		'	<body bgcolor="#eee">' .
		'	<table width="100%" cellpadding="0" cellspacing="0" border="0">' .
		'		<tr>' .
		'			<td width="100%" align="center">' .
        '				<table style="max-width: 700px" border="0" cellpadding="1" cellspacing="0"><tr><td>' .
		'   				<h1>' . $subject . '</h1>';

		$html .= '<h2>Message</h2> <div style="border: 1px solid #999; background: #fcfcfc; padding: 20px; margin-bottom: 40px">' . $exception->getMessage() . '</div>';

		$html .= '<h2>Info</h2> <div style="border: 1px solid #999; background: #fcfcfc; padding: 20px; margin-bottom: 40px">' . $error_info . '</div>';

		// Backtraces are not very useful for anything else but real exceptions
		if (!($exception instanceof \ErrorException)) {
			$backtrace_arr = debug_backtrace();
			$backtrace = '';
			foreach ($backtrace_arr as $key => $line) {
				if (isset($line['line'])) {
					$line_number = $line['line'];
				} else {
					$line_number = '-';
				}

				if (isset($line['file'])) {
					$filename = $line['file'];
				} else {
					$filename = '-';
				}

				$backtrace .= '[#' . $key . '] ' . $filename . ' <span style="color: #666">in</span> ' . $line['function'] . '  <span style="color: #666">at</span> line ' . $line_number;
				$backtrace .= '<pre>' . print_r($line['args'], true) . '</pre><br>';

				if ($key < count($backtrace_arr) - 1) {
					$backtrace .= '<hr><br>';
				}
			}

			$html .= '<h2>Backtrace</h2> <div style="border: 1px solid #999; background: #fcfcfc; padding: 20px; margin-bottom: 40px">' . $backtrace . '</div>';
		}

		$vartrace = [
			'_GET'      => isset($_GET) ? $_GET : null,
			'_POST'     => isset($_POST) ? $_POST : null,
			'_COOKIE'   => isset($_COOKIE) ? $_COOKIE : null,
			'_SESSION'  => isset($_SESSION) ? $_SESSION : null,
			'_SERVER'   => isset($_SERVER) ? $_SERVER : null
		];

		$html .= '<h2>Vartrace</h2> <div style="border: 1px solid #999; background: #fcfcfc; padding: 20px; margin-bottom: 40px"><pre>' . print_r($vartrace, true) . '</pre></div>';

		$html .=
		'						</td>' .
		'					</tr>' .
		'				</table>' .
		'			</td>' .
		'		</tr>' .
		'		</table>' .
		'   </body>' .
		'</html>';

		return $html;
	}
}

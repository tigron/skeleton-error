<?php
/**
 * Mail handler for for Skeleton\Error
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 * @author David Vandemaele <david@tigron.be>
 */

namespace Skeleton\Error\Handler;

class Mail extends Handler {

	/**
	 * Handle when reporting exception manually
	 *
	 * @var bool $reports
	 */
	protected $reports = true;

	/**
	 * Handle an error by sending mail
	 *
	 * @return string
	 */
	public function handle() {
		$subject = \Skeleton\Error\Handler\Basic::get_subject($this->exception);
		$message = \Skeleton\Error\Handler\Basic::get_html($this->exception);

		$headers = 'From: ' . \Skeleton\Error\Config::$mail_errors_from . "\r\n";
		$headers.= 'Content-Type: text/html; charset=ISO-8859-1 MIME-Version: 1.0';

		mail(\Skeleton\Error\Config::$mail_errors_to, $subject, $message, $headers, '-f ' . \Skeleton\Error\Config::$mail_errors_from);
	}

	/**
	 * Can this handler run?
	 *
	 * @return bool
	 */
	public function can_run() {
		// Mail can always run
		return true;
	}
}

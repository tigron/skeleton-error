<?php
/**
 * Config class
 * Configuration for Skeleton\Error
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error;

class Config {

	/**
	 * Debug
	 *
	 * Debug true will output the complete error
	 *
	 * @access public
	 * @var string $tmp_directory
	 */
	public static $debug = false;

	/**
	 * errors_from
	 *
	 * Send errors via email with from address
	 *
	 * @access public
	 * @var string $errors_from
	 */
	public static $mail_errors_from = 'info@example.com';

	/**
	 * errors_to
	 *
	 * Send errors to email address. Set to null to disable mailing completely.
	 *
	 * @access public
	 * @var string $errors_to
	 */
	public static $mail_errors_to = null;

	/**
	 * sentry_dsn
	 *
	 * If you have Sentry Raven installed, you can supply the Sentry DSN.
	 *
	 * @access public
	 * @var string $sentry_dsn;
	 */
	public static $sentry_dsn = null;
}

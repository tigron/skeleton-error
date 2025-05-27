<?php
/**
 * Configuration for Skeleton\Error
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
	 * Environment
	 *
	 * The environment identifier
	 */
	public static $environment = null;

	/**
	 * Release
	 *
	 * The release identifier
	 */
	public static $release = null;

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
	 * If you have sentry/sentry installed, you can supply the Sentry DSN.
	 *
	 * @access public
	 * @var string $sentry_dsn;
	 */
	public static $sentry_dsn = null;

	/**
	 * error_reporting
	 *
	 * Override PHP's error_reporting level
	 *
	 * @access public
	 * @var int $error_reporting
	 */
	public static int $error_reporting = E_ALL;
}

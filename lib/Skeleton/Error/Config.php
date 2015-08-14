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
	public static $errors_from = 'info@example.com';

	/**
	 * errors_to
	 *
	 * Send errors to email address
	 *
	 * @access public
	 * @var string $errors_to
	 */
	public static function $errors_to = 'info@example.com';


}

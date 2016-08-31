<?php
/**
 * Basic error detector for Skeleton
 *
 * If Basic is installed, it will check the provided string for an error.
 *
 * @author Lionel Laffineur <lionel@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error\Detector;

class Basic {

	/**
	 * Function detect
	 * Takes care of detecting if a Basic error was passed and if true
	 * extracting the error code to return it by reference
	 *
	 * @access public
	 * @param string $html code of the page to be analyzed
	 * @param string $error the error code found (if any)
	 * @return bool does the page contain an error
	 */
	public static function detect($html, &$error = '') {
		if (class_exists('Whoops\Run')) {
			return false;
		}

		if (strpos($html, '<h2>Message</h2> <pre>') != false) {
			preg_match('/<h2>Message<\/h2> <pre>(.*)<\/pre>/', $html, $output_array);
			$error = trim(strip_tags($output_array[0]));
			return true;
		}

		return false;
	}
}

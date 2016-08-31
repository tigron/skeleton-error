<?php
/**
 * BasicOutput error detector for Skeleton
 *
 * If BasicOutput is installed, it will check the provided string for an error.
 *
 * @author Lionel Laffineur <lionel@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error\Detector;

class BasicOutput {

	/**
	 * Function detect
	 * Takes care of detecting if a BasicOutput error was passed and if true
	 * returning the error code by reference
	 *
	 * @access public
	 * @param string $html code of the page to be analyzed
	 * @param string $error the error code found (if any)
	 * @return bool does the page contain an error
	 */
	public static function detect($html, &$error = '') {
		// FIXME: check for the debug flag
		if (strpos($html, 'An unexpected error has occurred. Please try again later.') != false) {
			$error = 'An unexpected error has occurred. Please try again later.';
			return true;
		}

		return false;
	}
}

<?php
/**
 * Whoops error detector for Skeleton
 *
 * If Whoops is installed, it will check the provided string for a whoops error.
 *
 * @author Lionel Laffineur <lionel@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error\Detector;

class Whoops {

	/**
	 * Function detect
	 * Takes care of detecting if a Whoops error was passed and if true
	 * extracting the error code to return it by reference
	 *
	 * @access public
	 * @param string $html code of the page to be analyzed
	 * @param string $error the error code found (if any)
	 * @return bool does the page contain an error
	 */
	public static function detect($html, &$error = '') {
		if (!class_exists('Whoops\Run')) {
			return false;
		}

		if (strpos($html, 'class="Whoops container"') != false) {
			// temporarily disables libxml warnings
			libxml_use_internal_errors(true);
			$dom = new \DOMDocument();
			$dom->loadHTML($html);
			$xpath = new \DOMXpath($dom);
			$results = $xpath->query("//span[@id='plain-exception']");
			libxml_use_internal_errors(false);

			if (!is_null($results)) {
				$error = $results[0]->nodeValue;
			}

			return true;
		}

		return false;
	}
}

<?php
/**
 * Error detector for Skeleton
 *
 * @author Lionel Laffineur <lionel@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error;

class Detector {

	private static $error_handlers = [
		'Whoops',
		'Basic',
		'BasicOutput',
	];

	public function detect($html, &$error = '') {

		foreach (self::$error_handlers as $handler) {
			$classname = '\\Skeleton\\Error\\Detector\\' . $handler;
			if ($classname::detect($html, $error)) {
				return true;
			}
		}

		return false;
	}
}

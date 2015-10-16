<?php
/**
 * Various utilities used in Skeleton\Error
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error\Util;

class Misc {
	/**
	 * Translate ErrorException code into the represented constant.
	 * Shamelessly stolen from Whoops, https://github.com/filp/whoops
	 *
	 * @param int $error_code
	 * @return string
	 */
	public static function translate_error_code($error_code) {
		$constants = get_defined_constants(true);
			if (array_key_exists('Core' , $constants)) {
				foreach ($constants['Core'] as $constant => $value) {
					if (substr($constant, 0, 2) == 'E_' && $value == $error_code) {
						return $constant;
				}
			}
		}

		return "E_UNKNOWN";
	}
}

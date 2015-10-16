<?php
/**
 * Wrapper class around Whoops\Run, setting different defaults.
 *
 * @author Christophe Gosiau <christophe@tigron.be>
 * @author Gerry Demaret <gerry@tigron.be>
 */

namespace Skeleton\Error\Util;

class WhoopsWrapper extends \Whoops\Run {
	protected $allowQuit = false;
	protected $sendOutput = false;
}

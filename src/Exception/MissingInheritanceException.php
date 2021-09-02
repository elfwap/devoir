<?php
namespace Devoir\Exception;

use \Throwable;

/**
 *
 * @namespace Devoir\Exception
 * @author Muhammad Tahir Abdullahi
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *
 */
class MissingInheritanceException extends DevoirException
{
	/**
	 *
	 * @param mixed $message
	 * @param int $code
	 * @param Throwable $previous
	 */
	public function __construct($message = null, int $code = null, Throwable $previous = null)
	{
		if(is_array($message) && count($message) <> 2){
			throw new DevoirException("Array argument must contain two (2) items. `" . count($message) . "` given.");
		}
		$this->template = "Class `%s` must extend class `%s`.";
		$this->code = MISSING_INHERITANCE_EXCEPTION_CODE;
		parent::__construct($message, $code, $previous);
	}

	/**
	 */
	function __destruct()
	{
	}
}


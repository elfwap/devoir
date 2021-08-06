<?php
namespace Devoir\Exception;

use \Throwable;
use \ReflectionClass;

/**
 *
 * @namespace Devoir\Exception
 * @author Muhammad Tahir Abdullahi
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *
 */
class MissingActionException extends DevoirException
{
	/**
	 *
	 * @param mixed $message
	 *        	[optional]
	 * @param int $code
	 *        	[optional]
	 * @param Throwable $previous
	 *        	[optional]
	 */
	public function __construct($message, ?int $code = null, ?Throwable $previous = null)
	{
		$this->template = "MissingActionException: Method `%s` of Controller `%s` is Not found.";
		$this->code = MISSING_ACTION_EXCEPTION_CODE;
		parent::__construct($message, $code, $previous);
	}
	
	/**
	 */
	function __destruct()
	{
		
		// TODO - Insert your code here
	}
	
	/**
	 * @return \Devoir\Exception\MissingActionException
	 */
	public static function newInstance($message, ?int $code = null, ?Throwable $previous = null) {
		return (new ReflectionClass(MissingActionException::class))->newInstanceArgs([$message, $code, $previous]);
	}
}


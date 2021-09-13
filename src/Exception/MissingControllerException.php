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
class MissingControllerException extends DevoirException
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
		$this->template = "Controller `%s` not found.";
		$this->code = MISSING_CONTROLLER_EXCEPTION_CODE;
		if(is_array($message) && count($message) == 2){
			$this->template .= " Additional info: %s.";
		}
		http_response_code(RESPONSE_CODE_NOT_FOUND);
		parent::__construct($message, $code, $previous);
	}
}


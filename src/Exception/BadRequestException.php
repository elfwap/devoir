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
class BadRequestException extends DevoirException
{
	/**
	 *
	 * @param mixed $message
	 * @param int $code
	 * @param Throwable $previous
	 */
	public function __construct($message = null, int $code = null, Throwable $previous = null)
	{
		if(is_array($message) && count($message) > 1){
			throw new DevoirException("Array argument must contain One (1) item or no argument. `" . count($message) . "` given.");
		}
		if(is_array($message) && count($message) == 1){
			if(is_string($message[0]) && !empty($message[0])) $this->template = "Bad Request! Additional Info: %s.";
		}
		elseif(is_null($message)){
			$message = "Bad Request!";
		}
		$this->code = BAD_REQUEST_EXCEPTION_CODE;
		http_response_code(RESPONSE_CODE_BAD_REQUEST);
		parent::__construct($message, $code, $previous);
	}
}


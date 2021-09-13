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
		if(is_array($message) && count($message) > 3){
			throw new DevoirException("Array argument must contain two (2) or three (3) items. `" . count($message) . "` given.");
		}
		$this->template = "Method `%s` of Controller `%s` not found.";
		$this->code = MISSING_ACTION_EXCEPTION_CODE;
		if(is_array($message) && count($message) == 3){
			$this->template .= " Additional info: %s.";
		}
		http_response_code(RESPONSE_CODE_NOT_FOUND);
		parent::__construct($message, $code, $previous);
	}
}


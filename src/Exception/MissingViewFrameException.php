<?php
namespace Devoir\Exception;

use \Throwable;

/**
 * Missing View Frame Exception, thrown when the specified view class is not found.
 * @namespace Devoir\Exception
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc. <https://github.com/elfwap/devoir/>
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
class MissingViewFrameException extends DevoirException
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
		$this->template = "View Frame `%s` not found.";
		$this->code = MISSING_VIEW_FRAME_EXCEPTION_CODE;
		if(is_array($message) && count($message) == 2){
			$this->template .= " Additional info: %s.";
		}
		http_response_code(RESPONSE_CODE_NOT_FOUND);
		parent::__construct($message, $code, $previous);
	}
}

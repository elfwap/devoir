<?php
namespace Devoir\Exception;

use \Throwable;

/**
 * Not Found Exception, thrown when the specified file or object is not found on the server.
 * @namespace Devoir\Exception
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *
 */
class NotFoundException extends DevoirException
{
	/**
	 *
	 * @param mixed $message
	 * @param int $code
	 * @param Throwable $previous
	 */
	public function __construct($message = null, int $code = null, Throwable $previous = null)
	{
		if(is_array($message) && count($message) <> 1){
			throw new DevoirException("Array argument must contain One (1) item. `" . count($message) . "` given.");
		}
		$this->template = "`%s` Not found.";
		$this->code = NOT_FOUND_EXCEPTION_CODE;
		http_response_code(RESPONSE_CODE_NOT_FOUND);
		parent::__construct($message, $code, $previous);
	}
}

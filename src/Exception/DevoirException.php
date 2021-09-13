<?php
namespace Devoir\Exception;

use \Throwable;
use \Exception;

/**
 *
 * @namespace Devoir\Exception
 * @author Muhammad Tahir Abdullahi
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
class DevoirException extends Exception
{
	/**
	 * 
	 * @var string	Template for formatting exception message
	 */
	protected $template  = '%s';
	/**
	 * 
	 * @var integer	excetion code
	 */
	protected $code = 0;

	/**
	 * 
	 * @param mixed $message
	 * @param int $code
	 * @param Throwable $previous
	 */
	public function __construct($message = null, ?int $code = null, ?Throwable $previous = null)
	{
		$code = (!is_null($code))? $code : $this->code;
		if(is_string($message) && strlen($message) > 0){
			parent::__construct($message, $code, $previous);
		}
		if(is_null($message)){
			$message = sprintf($this->template, "Exception thrown with missing message.");
			parent::__construct($message, $code, $previous);
		}
		if (is_array($message)) {
			$message = vsprintf($this->template, $message);
			parent::__construct($message, $code, $previous);
		}
	}	
}


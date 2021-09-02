<?php
namespace Devoir\Exception;

use \Throwable;
use \Exception;
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

    /**
     */
    function __destruct()
    {
	}

	/**
	 * 
	 * @example
	 * ```
	 * $x->setTemplate("InvalidArgumentException: Argument %s supplied on method: '%s' at line: (%d) in file [%s] is not a/an %s ");
	 * ```
	 * @param string	$template	String template to be formatted.
	 * @return \Devoir\Exception\DevoirException
	 */
	protected function setTemplate($template = '') {
		$this->template = $template;
		return $this;
	}
	/**
	 * 
	 * @example
	 * ```
	 * $x->setCode(256);
	 * ```
	 * @param int $code
	 * @return \Devoir\Exception\DevoirException
	 */
	protected function setCode($code = 0) {
		$this->code = $code;
		return $this;
	}
	/**
	 * 
	 * @param mixed $message
	 * @param Throwable $previous
	 * @return \Devoir\Exception\DevoirException
	 */
	protected function init($message = null, ?Throwable $previous = null) {
		$this->__construct($message, $this->code, $previous);
		return $this;
	}
	
	/**
	 * @return \Devoir\Exception\DevoirException
	 */
	public static function newInstance($message, ?int $code = null, ?Throwable $previous = null) {
		return (new ReflectionClass(static::class))->newInstanceArgs([$message, $code, $previous]);
	}
}


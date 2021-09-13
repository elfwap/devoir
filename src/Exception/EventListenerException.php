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
class EventListenerException extends DevoirException
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
	public function __construct($message = null, ?int $code = null, ?Throwable $previous = null)
	{
		if(is_array($message) && count($message) > 4){
			throw new DevoirException("Array argument must contain four (4) items or less. `" . count($message) . "` given.");
		}
		if(count($message) == 2 && is_bool($message[1]) && $message[1] === true){
			$this->template = "Several Event Listener Exception: %s.";
			$mstr = "";
			foreach ($message[0] as $mk => $msg){
				array_unshift($msg, ($mk + 1));
				$mstr .= vsprintf("(#%d) Event `%s` cannot invoke callback listener `%s` of class `%s`, class or callback function does'nt exists. Additional info: %s. \r\n", $msg);
			}
			$message = [$mstr];
		}
		elseif(count($message) == 4 && is_string($message[3])){
			$this->template = "Event `%s` cannot invoke callback listener `%s` of class `%s`, class or callback function does'nt exists. Additional info: %s.";
		}
		$this->code = EVENT_LISTENER_EXCEPTION_CODE;
		parent::__construct($message, $code, $previous);
	}
}


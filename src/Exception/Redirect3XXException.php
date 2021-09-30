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

class Redirect3XXException extends DevoirException
{
	/**
	 *
	 * @param mixed $message
	 * @param int $code
	 * @param Throwable $previous
	 */
	public function __construct($message = null, int $code = null, Throwable $previous = null)
	{
		if (is_array($message) && count($message) == 2) {
			$loc = "";
			$cod = 0;
			if (array_key_exists('location', $message)) {
				$loc = $message['location'];
			} elseif (array_key_exists('Location', $message)) {
				$loc = $message['Location'];
			} else {
				$loc = $message[0];
			}
			if (array_key_exists('code', $message)) {
				$cod = $message['code'];
			} elseif (array_key_exists('Code', $message)) {
				$cod = $message['Code'];
			} else {
				$cod = $message[1];
			}
			if($cod > 399 || $cod < 300) throw new DevoirException('Expected code from 300 to 399, ' . $cod . ' supplied!');
			switch ($cod) {
				#300
				case RESPONSE_CODE_MULTIPLE_CHOICES:
					$this->code = MULTIPLE_CHOICE_EXCEPTION_CODE;
					break;
				#301
				case RESPONSE_CODE_MOVED_PERMANENTLY:
					$this->code = MOVED_PERMANENTLY_EXCEPTION_CODE;
					break;
				#302
				case RESPONSE_CODE_MOVED_TEMPORARILY:
					$this->code = MOVED_TEMPORARILY_EXCEPTION_CODE;
					break;
				#303
				case RESPONSE_CODE_SEE_OTHER:
					$this->code = SEE_OTHER_EXCEPTION_CODE;
					break;
				#304
				case RESPONSE_CODE_NOT_MODIFIED:
					$this->code = NOT_MODIFIED_EXCEPTION_CODE;
					break;
				#305
				case RESPONSE_CODE_USE_PROXY:
					$this->code = USE_PROXY_EXCEPTION_CODE;
					break;
				#306
				case RESPONSE_CODE_SWITCH_PROXY:
					$this->code = SWITCH_PROXY_EXCEPTION_CODE;
					break;
				#307
				case RESPONSE_CODE_TEMPORARY_REDIRECT:
					$this->code = TEMPORARY_REDIRECT_EXCEPTION_CODE;
					break;
				#308
				case RESPONSE_CODE_PERMANENT_REDIRECT:
					$this->code = PERMANENT_REDIRECT_EXCEPTION_CODE;
					break;
				default:
					$this->code = $cod + 1000;
					break;
			}
			header('location: ' . $loc, yes, $cod);
			parent::__construct($message, $code, $previous);
			die();
		}
		else {
			throw new DevoirException("Argument must be a string or an array containing Two (2) items.");
		}
	}
}

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
class Server5XXException extends DevoirException
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
			$msg = "";
			$cod = 0;
			if (array_key_exists('message', $message)) $msg = $message['message'];
			elseif (array_key_exists('Message', $message)) $msg = $message['Message'];
			else $msg = $message[0];
			if (array_key_exists('code', $message)) $cod = $message['code'];
			elseif (array_key_exists('Code', $message)) $cod = $message['Code'];
			else $cod = $message[1];
			$message = [$msg];
			if($cod > 599 || $cod < 500) throw new DevoirException('Expected code from 500 to 599, ' . $cod . ' supplied!');
			switch ($cod) {
				#500
				case RESPONSE_CODE_INTERNAL_SERVER_ERROR:
					$this->code = INTERNAL_SERVER_ERROR_EXCEPTION_CODE;
					$this->template = 'Internal Server Error! Additional Info: %s.';
					break;
				#501
				case RESPONSE_CODE_NOT_IMPLEMENTED:
					$this->code = NOT_IMPLEMENTED_EXCEPTION_CODE;
					$this->template = 'Not Implemented! Additional Info: %s.';
					break;
				#502
				case RESPONSE_CODE_BAD_GATEWAY:
					$this->code = BAD_GATEWAY_EXCEPTION_CODE;
					$this->template = 'Bad Gateway! Additional Info: %s.';
					break;
				#503
				case RESPONSE_CODE_SERVICE_UNAVAILABLE:
					$this->code = SERVICE_UNAVAILABLE_EXCEPTION_CODE;
					$this->template = 'Service Unavailable! Additional Info: %s.';
					break;
				#504
				case RESPONSE_CODE_GATEWAY_TIMEOUT:
					$this->code = GATEWAY_TIMEOUT_EXCEPTION_CODE;
					$this->template = 'Gateway Timeout! Additional Info: %s.';
					break;
				#505
				case RESPONSE_CODE_HTTP_VERSION_NOT_SUPPORTED:
					$this->code = HTTP_VERSION_NOT_SUPPORTED_EXCEPTION_CODE;
					$this->template = 'HTTP Version Not Supported! Additional Info: %s.';
					break;
				#506
				case RESPONSE_CODE_VARIANT_ALSO_NEGOTIATES:
					$this->code = VARIANT_ALSO_NEGOTIATES_EXCEPTION_CODE;
					$this->template = 'Variant also Negotiates! Additional Info: %s.';
					break;
				#507
				case RESPONSE_CODE_INSUFFICIENT_STORAGE:
					$this->code = INSUFFICIENT_STORAGE_EXCEPTION_CODE;
					$this->template = 'Insufficient Storage! Additional Info %s.';
					break;
				#508
				case RESPONSE_CODE_LOOP_DETECTED:
					$this->code = LOOP_DETECTED_EXCEPTION_CODE;
					$this->template = 'Loop Detected! Additional Info: %s.';
					break;
				#510
				case RESPONSE_CODE_NOT_EXTENDED:
					$this->code = NOT_EXTENDED_EXCEPTION_CODE;
					$this->template = 'Not Extended! Additional Info: %s.';
					break;
				#511
				case RESPONSE_CODE_NETWORK_AUTHENTICATION_REQUIRED:
					$this->code = NETWORK_AUTHENTICATION_REQUIRED_EXCEPTION_CODE;
					$this->template = 'Network Authentication Required! Additional Info: %s.';
					break;
				default:
					$this->code = $cod + 1000;
					$this->template = 'Unknown Server Error: Additional Info: %s.';
					break;
			}
			http_response_code($cod);
		} elseif ((is_string($message) && !empty($message)) && (is_numeric($code) && $code > 1499 && $code < 1600)) {
			$this->template = "Server Error: %s.";
			http_response_code(($code - 1000));
		} else {
			throw new DevoirException("Argument must be a string or an array containing Two (2) items.");
		}
		parent::__construct($message, $code, $previous);
	}
}

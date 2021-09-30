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
class Client4XXException extends DevoirException
{
	/**
	 *
	 * @param mixed $message
	 * @param int $code
	 * @param Throwable $previous
	 */
	public function __construct($message = null, int $code = null, Throwable $previous = null)
	{
		if(is_array($message) && count($message) == 2){
			$msg = "";
			$cod = 0;
			if(array_key_exists('message', $message)) $msg = $message['message'];
			elseif(array_key_exists('Message', $message)) $msg = $message['Message'];
			else $msg = $message[0];
			if(array_key_exists('code', $message)) $cod = $message['code'];
			elseif(array_key_exists('Code', $message)) $cod = $message['Code'];
			else $cod = $message[1];
			$message = [$msg];
			if($cod > 499 || $cod < 400) throw new DevoirException('Expected code from 400 to 499, ' . $cod . ' supplied!');
			switch ($cod) {
				#400
				case RESPONSE_CODE_BAD_REQUEST:
					$this->code = BAD_REQUEST_EXCEPTION_CODE;
					$this->template = 'Bad Request! Additional Info: %s.';
					break;
				#401
				case RESPONSE_CODE_UNAUTHORIZED:
					$this->code = UNAUTHORIZED_EXCEPTION_CODE;
					$this->template = 'UnAuthorized! Additional Info: %s.';
					break;
				#402
				case RESPONSE_CODE_PAYMENT_REQUIRED:
					$this->code = PAYMENT_REQUIRED_EXCEPTION_CODE;
					$this->template = 'Payment Required! Additional Info: %s.';
					break;
				#403
				case RESPONSE_CODE_FORBIDDEN:
					$this->code = FORBIDDEN_EXCEPTION_CODE;
					$this->template = 'Forbidden! Additional Info: %s.';
					break;
				#404
				case RESPONSE_CODE_NOT_FOUND:
					$this->code = NOT_FOUND_EXCEPTION_CODE;
					$this->template = 'Not Found! Additional Info: %s.';
					break;
				#405
				case RESPONSE_CODE_METHOD_NOT_ALLOWED:
					$this->code = METHOD_NOT_ALLOWED_EXCEPTION_CODE;
					$this->template = 'Method! Additional Info: %s.';
					break;
				#406
				case RESPONSE_CODE_NOT_ACCEPTABLE:
					$this->code = NOT_ACCEPTABLE_EXCEPTION_CODE;
					$this->template = 'Not Acceptable! Additional Info: %s.';
					break;
				#407
				case RESPONSE_CODE_PROXY_AUTHENTICATION_REQUIRED:
					$this->code = PROXY_AUTHENTICATION_REQUIRED_EXCEPTION_CODE;
					$this->template = 'Proxy Authentication Required! Additional Info: %s.';
					break;
				#408
				case RESPONSE_CODE_REQUEST_TIMEOUT:
					$this->code = REQUEST_TIMEOUT_EXCEPTION_CODE;
					$this->template = 'Request Timeout! Additional Info: %s.';
					break;
				#409
				case RESPONSE_CODE_CONFLICT:
					$this->code = CONFLICT_EXCEPTION_CODE;
					$this->template = 'Conflict! Additional Info: %s.';
					break;
				#410
				case RESPONSE_CODE_GONE:
					$this->code = GONE_EXCEPTION_CODE;
					$this->template = 'Gone! Additional Info: %s.';
					break;
				#411
				case RESPONSE_CODE_LENGTH_REQUIRED:
					$this->code = LENGTH_REQUIRED_EXCEPTION_CODE;
					$this->template = 'Length Required! Additional Info: %s.';
					break;
				#412
				case RESPONSE_CODE_PRECONDITION_FAILED:
					$this->code = PRECONDITION_FAILED_EXCEPTION_CODE;
					$this->template = 'Precondition Failed! Additional Info: %s.';
					break;
				#413
				case RESPONSE_CODE_PAYLOAD_TOO_LARGE:
					$this->code = PAYLOAD_TOO_LARGE_EXCEPTION_CODE;
					$this->template = 'Payload too Large! Additional Info: %s.';
					break;
				#414
				case RESPONSE_CODE_URI_TOO_LONG:
					$this->code = URI_TOO_LONG_EXCEPTION_CODE;
					$this->template = 'URI Too Long! Additional Info: %s.';
					break;
				#415
				case RESPONSE_CODE_UNSUPPORTED_MEDIA_TYPE:
					$this->code = UNSUPPORTED_MEDIA_TYPE_EXCEPTION_CODE;
					$this->template = 'Unsupported Media Type! Additional Info: %s.';
					break;
				#416
				case RESPONSE_CODE_RANGE_NOT_SATISFIABLE:
					$this->code = RANGE_NOT_SATISFIABLE_EXCEPTION_CODE;
					$this->template = 'Range not Satisfiable! Additional Info: %s.';
					break;
				#417
				case RESPONSE_CODE_EXPECTATION_FAILED:
					$this->code = EXPECTATION_FAILED_EXCEPTION_CODE;
					$this->template = 'Expectation Failed! Additional Info: %s.';
					break;
				#418
				case RESPONSE_CODE_TEAPOT:
					$this->code = TEAPOT_EXCEPTION_CODE;
					$this->template = 'Teapot! Additional Info: %s.';
					break;
				#421
				case RESPONSE_CODE_MISDIRECTED_REQUEST:
					$this->code = MISDIRECTED_REQUEST_EXCEPTION_CODE;
					$this->template = 'Misdirected Request! Additional Info: %s.';
					break;
				#422
				case RESPONSE_CODE_UNPROCESSABLE_ENTITY:
					$this->code = UNPROCESSABLE_ENTITY_EXCEPTION_CODE;
					$this->template = 'Unprocessable Entity! Additional Info: %s.';
					break;
				#423
				case RESPONSE_CODE_LOCKED:
					$this->code = LOCKED_EXCEPTION_CODE;
					$this->template = 'Locked! Additional Info: %s.';
					break;
				#424
				case RESPONSE_CODE_FAILED_DEPENDENCY:
					$this->code = FAILED_DEPENDENCY_EXCEPTION_CODE;
					$this->template = 'Failed Dependency! Additional Info: %s.';
					break;
				#425
				case RESPONSE_CODE_TOO_EARLY:
					$this->code = TOO_EARLY_EXCEPTION_CODE;
					$this->template = 'Too Early! Additional Info: %s.';
					break;
				#426
				case RESPONSE_CODE_UPGRADE_REQUIRED:
					$this->code = UPGRADE_REQUIRED_EXCEPTION_CODE;
					$this->template = 'Upgrade Required! Additional Info: %s.';
					break;
				#428
				case RESPONSE_CODE_PRECONDITION_REQUIRED:
					$this->code = PRECONDITION_REQUIRED_EXCEPTION_CODE;
					$this->template = 'Precondition Required! Additional Info: %s.';
					break;
				#429
				case RESPONSE_CODE_TOO_MANY_REQUESTS:
					$this->code = TOO_MANY_REQUESTS_EXCEPTION_CODE;
					$this->template = 'Too Many Requests! Additional Info: %s.';
					break;
				#431
				case RESPONSE_CODE_REQUEST_HEADER_FIELDS_TOO_LARGE:
					$this->code = REQUEST_HEADER_FIELDS_TOO_LARGE_EXCEPTION_CODE;
					$this->template = 'Request Header Field too Large! Additional Info: %s.';
					break;
				#451
				case RESPONSE_CODE_UNAVAILABLE_FOR_LEGAL_REASONS:
					$this->code = UNAVAILABLE_FOR_LEGAL_REASONS_EXCEPTION_CODE;
					$this->template = 'Unavailable for Legal Reasons! Additional Info: %s.';
					break;
				default:
					$this->code = $cod + 1000;
					$this->template = 'Unknown Client Error! Additional Info: %s';
				break;
			}
			http_response_code($cod);
		}
		elseif((is_string($message) && !empty($message)) && (is_numeric($code) && $code > 1399 && $code < 1500)){
			$this->template = "Client Error: %s.";
			http_response_code(($code - 1000));
		}
		else{
			throw new DevoirException("Argument must be a string or an array containing Two (2) items.");
		}
		parent::__construct($message, $code, $previous);
	}
}


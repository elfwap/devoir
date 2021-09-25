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
        if (is_array($message) && count($message) == 3) {
			$loc = "";
            $msg = "";
            $cod = 0;
            if (array_key_exists('message', $message)) {
                $msg = $message['message'];
            } elseif (array_key_exists('Message', $message)) {
                $msg = $message['Message'];
            } else {
                $msg = $message[0];
            }
            if (array_key_exists('code', $message)) {
                $cod = $message['code'];
            } elseif (array_key_exists('Code', $message)) {
                $cod = $message['Code'];
            } else {
                $cod = $message[1];
            }
            $message = [$msg];
            switch ($cod) {
				
				case RESPONSE_CODE_MULTIPLE_CHOICES:
					$this->code = MULTIPLE_CHOICE_EXCEPTION_CODE;
					$this->template = 'MUltiple choices. Additional Info: %s.';
					break;
				
            }
        }
	}
 }
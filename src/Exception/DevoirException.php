<?php
namespace Devoir\Exception;

use \Throwable;
use \Exception;

/**
 *
 * @author Muhammad Tahir Abdullahi
 * @copyright Copyright (c) Elftech Inc.
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
class DevoirException extends Exception
{

    // TODO - Insert your code here

    /**
     *
     * @param mixed $message
     *            [optional]
     * @param mixed $code
     *            [optional]
     * @param mixed $previous
     *            [optional]
     */
    public function __construct($message = null, ?int $code = null, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     */
    function __destruct()
    {

        // TODO - Insert your code here
    }
    
    /**
     * 
     */
    protected function setTemplate($s) {
        ;
    }
}


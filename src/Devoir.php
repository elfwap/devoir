<?php
namespace Devoir;

/**
 *
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */

class Devoir
{
    /**
     */
    public function __construct()
    {
    }

    
    /**
     * Returns array of ancestral classes
     * starting from the first super class to the immediate parent class
     * @return array
     */
    protected function Ancestors():array
    {
    	return [Devoir::class];
    }
}


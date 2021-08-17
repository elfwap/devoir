<?php
namespace Devoir\Interfaces;

/**
 * DevoirEventInterface
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
interface DevoirEventInterface
{
	public function initialize();
	public function terminate();
}
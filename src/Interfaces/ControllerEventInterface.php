<?php
namespace Devoir\Interfaces;

/**
 * ControllerEventInterface
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
interface ControllerEventInterface extends DevoirEventInterface
{
	public function initialize();
	public function beforeRunUp();
	public function afterRunUp();
	public function beforeDispatch();
	public function afterDispatch();
	public function terminate();
}


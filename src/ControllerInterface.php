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
interface ControllerInterface
{
	/**
	 * 
	 * @param mixed $controllerName
	 */
	public function setController($controllerName);
	public function setAction($actionName);
	public function setParams(?array $params);
	public function setViewVar($params);
	public function getViewVars();
	public function run();
}


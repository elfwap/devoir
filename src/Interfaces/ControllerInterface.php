<?php
namespace Devoir\Interfaces;

/**
 *
 * @namespace Devoir\Interfaces
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
	 * @param string $controllerName
	 * @return object
	 */
	public function setController($controllerName);
	/**
	 * 
	 * @param string $actionName
	 * @return object
	 */
	public function setAction($actionName);
	/**
	 * 
	 * @param array $params
	 * @return object
	 */
	public function setParams(?array $params);
	/**
	 * 
	 * @param mixed $params
	 * @return object
	 */
	public function setViewVar($params);
	/**
	 * @return array
	 */
	public function getViewVars();
	/**
	 * @return object
	 */
	public function run();
}


<?php
namespace Devoir\Interfaces;

/**
 * Controller Interface, should be implemented by the front controller.
 * @namespace Devoir\Interfaces
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
interface ControllerInterface
{
	/**
	 * Sets the child controller's name.
	 * @param string $controllerName
	 * @return object
	 */
	public function setController($controllerName);
	/**
	 * Sets the action name to the current child controller.
	 * @param string $actionName
	 * @return object
	 */
	public function setAction($actionName);
	/**
	 * Sets the parameters to the current action of the current child controller.
	 * @param array $params
	 * @return object
	 */
	public function setParams(?array $params);
	/**
	 * Sets the variable(s) to the current view.
	 * @param mixed $params
	 * @return object
	 */
	public function setViewVar($params);
	/**
	 * Returns the view variable(s) of the current view.
	 * @return array
	 */
	public function getViewVars();
	/**
	 * Runs the initially specified controller.
	 * @return void
	 */
	public function run();
}

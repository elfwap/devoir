<?php
namespace Devoir\Interfaces;

use Devoir\Configuration;

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
	 * @return \Devoir\Interfaces\ControllerInterface
	 */
	public function setController($controllerName): ControllerInterface;
	/**
	 * Sets the action name to the current child controller.
	 * @param string $actionName
	 * @return \Devoir\Interfaces\ControllerInterface
	 */
	public function setAction($actionName): ControllerInterface;
	/**
	 * Sets the parameters to the current action of the current child controller.
	 * @param array $params
	 * @return \Devoir\Interfaces\ControllerInterface
	 */
	public function setParams(?array $params): ControllerInterface;
	/**
	 * Sets the variable(s) to the current view.
	 * @param mixed $params
	 * @return \Devoir\Interfaces\ControllerInterface
	 */
	public function setViewVar($var_name, $var_value = null): ControllerInterface;
	/**
	 * Returns the view variable(s) of the current view.
	 * @return array|iterable
	 */
	public function getViewVars(): iterable;
	/**
	 * Runs the initially specified controller.
	 * @return void
	 */
	public function run(): void;
	/**
	* Manually set the view to the current controller
	* @param string|null $view_frame
	* @param string|null $view_layout
	* @param null|string $view_class
	* 
	* @return \Devoir\Interfaces\ControllerInterface
	*/
	public function setView(?string $view_frame = null, ?string $view_layout = null, ?string $view_class = null): ControllerInterface;
	/**
	* Returns an iterable of view containing view layout, view frame and or view class
	* 
	* @return array|iterable
	*/
	public function getView(): iterable;
	/**
	 * Controller function to set Configuration data
	 * @param string $key
	 * @param mixed $value
	 * @param string $subkeys
	 * @return \Devoir\Interfaces\ControllerInterface
	 */
	public function setConfigData(string $key, $value, ?string $subkeys = null): ControllerInterface;
	/**
	 * Controller function to get Configuration data
	 * @param string $key
	 * @param string $subkeys
	 * @return mixed|object|iterable
	 */
	public function getConfigData(?string $key, ?string $subkeys = null);
	/**
	 * Gets the configuration object
	 * @return Configuration
	 */
	public function getConfig(): Configuration;
	/**
	* Sets the callback configuration object
	* @param Configuration $config
	* 
	* @return \Devoir\Interfaces\ControllerInterface
	*/
	public function setConfig(Configuration $config);
}

<?php
namespace Devoir\Interfaces;

use Devoir\Configuration;

/**
 * Model Interface, should be implemented by the Models.
 * @namespace Devoir\Interfaces
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
interface ModelInterface
{
	/**
	 * Sets the model name to be worked on.
	 * @param string $modelName
	 * @return \Devoir\Interfaces\ModelInterface
	 */
	public function setModel($modelName): ModelInterface;
	/**
	 * Gets the current model name
	 * @return string
	 */
	public function getModel(): string;
	/**
	 * Sets the variable(s) to the current view.
	 * @param mixed $params
	 * @return \Devoir\Interfaces\ModelInterface
	 */
	public function setViewVar($var_name, $var_value = null): ModelInterface;
	/**
	 * Returns the view variable(s) of the current view.
	 * @return array|iterable
	 */
	public function getViewVars(): iterable;
	/**
	 * Dispatches request data to model
	 * @return void
	 */
	public function dispatch(): void;
	/**
	 * Controller function to set Configuration data
	 * @param string $key
	 * @param mixed $value
	 * @param string $subkeys
	 * @return \Devoir\Interfaces\ModelInterface
	 */
	public function setConfigData(string $key, $value, ?string $subkeys = null): ModelInterface;
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
	 * @return \Devoir\Interfaces\ModelInterface
	 */
	public function setConfig(Configuration $config);
}


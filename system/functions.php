<?php

use Devoir\Devoir;
use Devoir\Controller;
use Devoir\View;
use Devoir\Configuration;

if(!function_exists("isController")){
	/**
	 * Checks whether the value of argument passed `is a` Devoir\Controller.
	 * @param mixed|object $component
	 * @return bool
	 */
    function isController($component): bool
	{
        return is_a($component, Controller::class, true);
       
    }
}
/* if(!function_exists("isModel")){
	function isModel($component): bool
	{
		return is_a($component, Model::class, true);
	}
} */
if(!function_exists("isView")){
	function isView($component): bool
	{
		return is_a($component, View::class, true);
	}
}
if(!function_exists("isDevoir")){
	/**
	 * Checks if a component inherits from `Devoir` class.
	 * @param string|object $component
	 * @return bool
	 */
	function isDevoir($component) {
		return is_a($component, Devoir::class, true);
	}
}
/**
 * Checks whether the passed argument value is null
 * @param mixed $value the value to passed as argument.
 * @return bool
 */
function isNull(mixed $value): bool{
	return ($value === null);
}
/**
* Global function to get application configuration data.
* @param string $key
* @param string|null $subkeys
* 
* @return mixed|object
*/
function getConfig($key, $subkeys = null) {
	$config = new Configuration(APP_SYSTEM_PATH);
	return $config->get($key, $subkeys);
}
/**
* Global function to set application configuration data.
* @param string $key
* @param string $value
* @param string|null $subkeys
* @see Devoir\Configuration::set()
* 
* @return Devoir\Configuration
*/
function setConfig($key, $value, $subkeys = null) {
	$config = new Configuration(APP_SYSTEM_PATH);
	return $config->set($key, $value, $subkeys);
}

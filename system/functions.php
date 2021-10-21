<?php

use Devoir\Devoir;
use Devoir\Controller;

if(!function_exists("isController")){
	/**
	 * Checks whether the value of argument passed `is a` Devoir\Controller. 
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
}
if(!function_exists("isView")){
	function isView($component): bool
	{
		return is_a($component, View::class, true);
	}
} */
if(!function_exists("isDevoir")){
	function isDevoir($component) {
		return is_a($component, Devoir::class, true);
	}
}
/**
 * Checks whether the passed argument value is null
 * @param mixed $value the value to passed as argument.
 * @return bool true|false
 */
function isNull(mixed $value): bool{
	return ($value === null);
}
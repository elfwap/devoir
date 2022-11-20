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

function getResponseTitles($code = null) {
	$lists = [];
	foreach (get_defined_constants() as $k => $gdc) {
		if (strpos($k, 'RESPONSE_CODE_') === 0) {
			$lists[$gdc] = ucwords(implode(' ', explode('_', strtolower(substr($k, strlen('RESPONSE_CODE_'))))));
		}
	}
	if (is_int($code)) {
		if (array_key_exists($code, $lists)) return $lists[$code];
	}
	return $lists;
}

function getResponseCodes($title = null) {
	$lists = [];
	foreach (get_defined_constants() as $k => $gdc) {
		if (strpos($k, 'RESPONSE_CODE_') === 0) {
			$lists[$k] = $gdc;
		}
	}
	if (is_string($title)) {
		$key = 'RESPONSE_CODE_' . strtoupper(str_replace(' ', '_', $title));
		if (array_key_exists($key, $lists)) return $lists[$key];
	}
	return $lists;
}


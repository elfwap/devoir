<?php
namespace Devoir;

if(!function_exists("isController")){
    function isController($component) {
        return is_a($component, 'Controller');
    }
}
if(!function_exists("isModel")){
	function isModel($component) {
		return is_a($component, 'Model');
	}
}
if(!function_exists("isView")){
	function isView($component) {
		return is_a($component, 'View');
	}
}
if(!function_exists("isDevoir")){
	function isDevoir($component) {
		return is_a($component, 'Devoir');
	}
}
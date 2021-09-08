<?php

if(!function_exists("isController")){
    function isController($component) {
        return is_a($component, Controller::class, true);
    }
}
/* if(!function_exists("isModel")){
	function isModel($component) {
		return is_a($component, Model::class, true);
	}
}
if(!function_exists("isView")){
	function isView($component) {
		return is_a($component, View::class, true);
	}
} */
if(!function_exists("isDevoir")){
	function isDevoir($component) {
		return is_a($component, Devoir::class, true);
	}
}

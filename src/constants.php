<?php
namespace Devoir;

if (!defined('YES')) {
    define('YES', true);
}
if (!defined('Yes')) {
    define('Yes', true);
}
if (!defined('yes')) {
    define('yes', true);
}
if (!defined('NO')) {
    define('NO', false);
}
if (!defined('No')) {
    define('No', false);
}
if (!defined('no')) {
    define('no', false);
}

if(!defined('SEC_SECOND'))
    define('SEC_SECOND', 1);
if(!defined('SEC_MINUTE'))
    define('SEC_MINUTE', 60);
if(!defined('SEC_HOUR'))
    define('SEC_HOUR', 3600);
if(!defined('SEC_DAY'))
    define('SEC_DAY', 86400);
if(!defined('SEC_WEEK'))
    define('SEC_WEEK', 604800);
if(!defined('SEC_MONTH'))
    define('SEC_MONTH', 2592000);
if(!defined('SEC_YEAR'))
    define('SEC_YEAR', 31536000);
if(!defined('MIL_SECOND'))
	define('MIL_SECOND', 1000);
if(!defined('MIL_MINUTE'))
	define('MIL_MINUTE', 60000);
if(!defined('MIL_HOUR'))
	define('MIL_HOUR', 3600000);
if(!defined('MIL_DAY'))
	define('MIL_DAY', 86400000);
if(!defined('MIL_WEEK'))
	define('MIL_WEEK', 604800000);
if(!defined('MIL_MONTH'))
	define('MIL_MONTH', 2592000000);
if(!defined('MIL_YEAR'))
	define('MIL_YEAR', 31536000000);
if(!defined('APPLICATION_NAMESPACE')){
	define('APPLICATION_NAMESPACE', 'App\\');
}
if(!defined('DEFAULT_CONTROLLER')){
	define('DEFAULT_CONTROLLER', 'AppController');
}
if (!defined('DEFAULT_ACTION')) {
	define('DEFAULT_ACTION', 'index');
}
if(!defined('DEVOIR_EXCEPTION_CODE')){
	define('DEVOIR_EXCEPTION_CODE', 1001);
}
if(!defined('MISSING_CONTROLLER_EXCEPTION_CODE')){
	define('MISSING_CONTROLLER_EXCEPTION_CODE', 1002);
}
if(!defined('MISSING_ACTION_EXCEPTION_CODE')){
	define('MISSING_ACTION_EXCEPTION_CODE', 1003);
}
if(!defined('DS')){
	define('DS', DIRECTORY_SEPARATOR);
}
if(!defined('ROOT_PATH')){
	define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
}
if(!defined('BASE_PATH')){
	$a = str_replace(ROOT_PATH, "", dirname(__FILE__));
	$a = str_replace('\/', DS, $a);
	$a = trim($a, DS);
	$b = 0;
	$a = str_replace('vendor' . DS . 'elfwap' . DS . 'devoir' . DS . 'src', "", $a, $b);
	if($b < 1) 
		define('BASE_PATH', "");
	else
		define('BASE_PATH', $a);
}
else{
	define('BASE_PATH', str_replace('\/', DS, BASE_PATH));
}
if(!defined('CONTROLLERS_PATH')){
	define('CONTROLLERS_PATH', (empty(BASE_PATH) ? BASE_PATH : (BASE_PATH . DS)) . "Controllers" . DS);
}
else{
	define('CONTROLLERS_PATH', str_replace('\/', DS, CONTROLLERS_PATH));
}
if(!defined('MODELS_PATH')){
	define('MODELS_PATH', (empty(BASE_PATH) ? BASE_PATH : (BASE_PATH . DS)) . "Models" . DS);
}
else{
	define('MODELS_PATH', str_replace('\/', DS, MODELS_PATH));
}
if(!defined('VIEWS_PATH')){
	define('VIEWS_PATH', (empty(BASE_PATH) ? BASE_PATH : (BASE_PATH . DS)) . "Views" . DS);
}
else{
	define('VIEWS_PATH', str_replace('\/', DS, VIEWS_PATH));
}
if(!defined('URL_TYPE_QUERY')){
	define('URL_TYPE_QUERY', 2000);
}
if(!defined('URL_TYPE_SLASH')){
	define('URL_TYPE_SLASH', 2001);
}
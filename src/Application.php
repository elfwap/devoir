<?php
namespace Devoir;

/**
 *
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
class Application
{
	
	public function __construct($systemDir = null)
	{
		if(is_dir($systemDir)){
			$constants = $systemDir . DIRECTORY_SEPARATOR . 'constants.php';
			$functions = $systemDir . DIRECTORY_SEPARATOR . 'functions.php';
			if(is_file($constants) && file_exists($constants)){
				require_once $constants;
			}
			if(is_file($functions) && file_exists($functions)){
				require_once $functions;
			}
		}
		$devoirSystemDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'system';
		if(is_dir($devoirSystemDir)){
			$devoirConstants = $devoirSystemDir . DIRECTORY_SEPARATOR . 'constants.php';
			$devoirFunctions = $devoirSystemDir . DIRECTORY_SEPARATOR . 'functions.php';
			if(is_file($devoirConstants) && file_exists($devoirConstants)) require_once $devoirConstants;
			if(is_file($devoirFunctions) && file_exists($devoirFunctions)) require_once $devoirFunctions;
		}
		$controller = new Controller(null, null, [], $systemDir);
		$controller->run();
	}
}


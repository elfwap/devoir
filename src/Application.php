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
	/**
	 */
	public function __construct($systemDir)
	{
		$controller = new Controller();
		$controller->registerListener(EVENT_ON_INITIALIZE, EVENT_ON_INITIALIZE);
		$controller->dispatchEvent(EVENT_ON_INITIALIZE);
		$controller->run();
	}

	/**
	 */
	function __destruct()
	{

		// TODO - Insert your code here
	}
}


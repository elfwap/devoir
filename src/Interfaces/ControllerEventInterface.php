<?php
namespace Devoir\Interfaces;

/**
 * ControllerEventInterface
 * @namespace Devoir\Interfaces
 * @author Muhammad Tahir Abdullahi
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
interface ControllerEventInterface extends DevoirEventInterface
{
	/**
	 * 
	 * @param DevoirEventInterface $event
	 */
	public function beforeRunUp(ControllerEventInterface $event);
	/**
	 * 
	 * @param DevoirEventInterface $event
	 */
	public function afterRunUp(ControllerEventInterface $event);
	/**
	 * 
	 * @param DevoirEventInterface $event
	 */
	public function beforeDispatch(ControllerEventInterface $event);
	/**
	 * 
	 * @param DevoirEventInterface $event
	 */
	public function afterDispatch(ControllerEventInterface $event);
	/**
	 * 
	 * @param ControllerEventInterface $event
	 */
	public function beforeManifest(ControllerEventInterface $event);
	/**
	 * 
	 * @param ControllerEventInterface $event
	 */
	public function afterManifest(ControllerEventInterface $event);
}


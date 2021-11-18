<?php
namespace Devoir\Interfaces;

/**
 * DevoirEventInterface
 * @namespace Devoir\Interfaces
 * @author Muhammad Tahir Abdullahi
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
interface DevoirEventInterface
{
	/**
	 * 
	 * Register new Listener for the current event
	 * @param string $event
	 * @param string|callable $callback
	 * @param object $object
	 */
	public function registerListener($event, $callback, $object = null);
	/**
	 * 
	 * @param string $event
	 */
	public function dispatchEvent($event);
	/**
	 * 
	 * Return an iterable object of registered listeners for current event
	 * @param string $event
	 * @return iterable
	 */
	public function getListenersForEvent($event): iterable;
	/**
	 * 
	 * @return iterable
	 */
	public function getImplementedListeners(): iterable;
	/**
	 * 
	 * When  Event Initializes
	 * @param DevoirEventInterface $event
	 */
	public function onInitialize(DevoirEventInterface $event);
	/**
	 * 
	 * When Event Terminate
	 * @param DevoirEventInterface $event
	 */
	public function onTerminate(DevoirEventInterface $event);
	/**
	 * 
	 * Returns True if current event is stopped
	 * @return bool
	 */
	public function isPropagationStopped() : bool;
}

<?php
namespace Devoir\Interfaces;

/**
 * DevoirEventInterface, Root event interface.
 * @namespace Devoir\Interfaces
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
interface DevoirEventInterface
{
	/**
	 * 
	 * Registers a new Listener for the current event
	 * @param string $event
	 * @param string|callable $callback
	 * @param object $object
	 */
	public function registerListener($event, $callback, $object = null);
	/**
	 * Dispatches the specified event.
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
	 * Gets the list of implemented listeners to the current class.
	 * @return iterable
	 */
	public function getImplementedListeners(): iterable;
	/**
	 * 
	 * Invoked when Event `initialize`s.
	 * @param DevoirEventInterface $event
	 */
	public function onInitialize(DevoirEventInterface $event);
	/**
	 * 
	 * Invoked when Event `terminate`s.
	 * @param DevoirEventInterface $event
	 */
	public function onTerminate(DevoirEventInterface $event);
	/**
	 * 
	 * When argument is supplied it checked if the event is stopped
	 * otherwise the entire events
	 * @param mixed $event event name
	 * @return bool
	 */
	public function isPropagationStopped($event = null) : bool;
	/**
	* Stops the propagation of the specified event or entire events otherwise preventing its listeners from handling it/them
	* @param mixed $event event name
	* 
	*/
	public function consumeEvent($event = null);
}

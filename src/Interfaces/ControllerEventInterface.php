<?php
namespace Devoir\Interfaces;

/**
 * ControllerEventInterface, Interface for controller's events.
 * @namespace Devoir\Interfaces
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
interface ControllerEventInterface extends DevoirEventInterface
{
	/**
	 * Invoked before the application runs, before calling the `run` function.
	 * @param ControllerEventInterface $event
	 */
	public function beforeRunUp(ControllerEventInterface $event);
	/**
	 * Invoked after the application runs, after calling the run function successfully.
	 * @param ControllerEventInterface $event
	 */
	public function afterRunUp(ControllerEventInterface $event);
	/**
	 * Invoked before data are being dispatched to the `Models`. 
	 * @param ControllerEventInterface $event
	 */
	public function beforeDispatch(ControllerEventInterface $event);
	/**
	 * Invoked after data are being dispatched to the `Models` successfully.
	 * @param ControllerEventInterface $event
	 */
	public function afterDispatch(ControllerEventInterface $event);
	/**
	 * Invoked before the manifestation of the current `View`.
	 * @param ControllerEventInterface $event
	 */
	public function beforeManifest(ControllerEventInterface $event);
	/**
	 * Invoked after the manifest of the current `View`.
	 * @param ControllerEventInterface $event
	 */
	public function afterManifest(ControllerEventInterface $event);
}

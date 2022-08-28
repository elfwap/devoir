<?php
namespace Devoir\Interfaces;

/**
 * ViewEventInterface, Interface for View's events.
 * @namespace Devoir\Interfaces
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
 interface ViewEventInterface extends DevoirEventInterface
 {
 	/**
	 * Invoked before the view starts to render
	 * @param ViewEventInterface $event
	 * 
	 */
 	public function beforeRender(ViewEventInterface $event);
 	/**
	 * Invoked after the view is completely rendered
	 * @param ViewEventInterface $event
	 * 
	 */
 	public function afterRender(ViewEventInterface $event);
 	/**
	 * Invoked before the view starts to render layout file
	 * @param ViewEventInterface $event
	 * 
	 */
 	public function beforeLayout(ViewEventInterface $event);
 	/**
	 * Invoked after the layout file is completely rendered
	 * @param ViewEventInterface $event
	 * 
	 */
 	public function afterLayout(ViewEventInterface $event);
 	/**
	 * Invoked before the view starts to render the frame file
	 * @param ViewEventInterface $event
	 * 
	 */
 	public function beforeFrame(ViewEventInterface $event);
 	/**
	 * Invoked after the frame file is completely rendered
	 * @param ViewEventInterface $event
	 * 
	 */
 	public function afterFrame(ViewEventInterface $event);
 }
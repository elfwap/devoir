<?php
namespace Devoir\Interfaces;

/**
 * View Interface, should be implemented by the view classes.
 * @namespace Devoir\Interfaces
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */

interface ViewInterface
{
	/**
	* Exports varaibles to view files.
	* @param array $var_array
	* 
	* @return object
	*/
	public function exportVars(array $var_array);
	/**
	* Returns and iterable (array|object) of exported vatiables
	* 
	* @return iterable
	*/
	public function exportedVars(): iterable;
	/**
	* Sets the layout file
	* @param string $name filename without the extension name
	* 
	* @return object
	*/
	public function setLayout(string $name);
	/**
	* Returns the layout file name
	* 
	* @return string
	*/
	public function getLayout(): string;
	/**
	* Sets the frame file to the current view
	* @param string $name
	* 
	* @return object
	*/
	public function setFrame(string $name);
	/**
	* Returns the frame name of the current view
	* 
	* @return string
	*/
	public function getFrame(): string;
	/**
	* Render the current view
	* 
	* @return object
	*/
	public function render();
	/**
	* Sets view class especially to handle view events
	* @param string $class_name
	* 
	* @return object
	*/
	public function setClass(string $class_name);
	/**
	* Gets view class for the current view
	* 
	* @return string
	*/
	public function getClass(): string;
	/**
	* 
	* @param \Devoir\Interfaces\ControllerInterface $callback_controller
	* 
	* @return
	*/
	public function setCallback(ControllerInterface $callback_controller);
}
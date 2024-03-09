<?php
namespace Devoir\Interfaces;

use Devoir\Configuration;

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
	* @return \Devoir\Interfaces\ViewInterface
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
	* @return \Devoir\Interfaces\ViewInterface
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
	* @return void
	*/
	public function render(): void;
	/**
	* Sets view class especially to handle view events
	* @param string $class_name
	* 
	* @return \Devoir\Interfaces\ViewInterface
	*/
	public function setClass(string $class_name);
	/**
	* Gets view class for the current view
	* 
	* @return string
	*/
	public function getClass(): string;
	/**
	* Sets the view's HTML title
	* @param string $text
	* 
	* @return \Devoir\Interfaces\ViewInterface
	*/
	public function setTitle(string $text = "untitled");
	/**
	* Gets the view's HTML title
	* 
	* @return string
	*/
	public function getTitle(): string;
	/**
	 * Controller function to set Configuration data
	 * @param string $key
	 * @param mixed $value
	 * @param string $subkeys
	 * @return \Devoir\Interfaces\ViewInterface
	 */
	public function setConfigData(string $key, $value, ?string $subkeys = null): ViewInterface;
	/**
	 * Controller function to get Configuration data
	 * @param string $key
	 * @param string $subkeys
	 * @return mixed|object|iterable
	 */
	public function getConfigData(?string $key, ?string $subkeys = null);
	/**
	 * Gets the configuration object
	 * @return Configuration
	 */
	public function getConfig(): Configuration;
	/**
	* Sets the callback configuration object
	* @param Configuration $config
	* 
	* @return \Devoir\Interfaces\ViewInterface
	*/
	public function setConfig(Configuration $config);
}
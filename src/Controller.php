<?php
namespace Devoir;

use \ReflectionClass;

/**
 *
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
class Controller extends Devoir implements ControllerInterface
{

	protected $controller = DEFAULT_CONTROLLER;
	protected $action = DEFAULT_ACTION;
	protected $basePath;
	protected $controllerParams = array();
	protected $viewVars = array();

	/**
	 */
	public function __construct()
	{

		// TODO - Insert your code here
	}

	/**
	 */
	function __destruct()
	{

		// TODO - Insert your code here
	}
	public function setAction($actionName)
	{
		$this->action = $actionName;
		return $this;
	}

	public function setViewVar($var)
	{
		$this->viewVars[] = $var;
		return $this;
	}

	public function getViewVars()
	{
		return $this->viewVars;
	}

	public function run()
	{
		$a = null;
		if(file_exists($this->controller)){
			$a = new ReflectionClass($this->controller);
		}
		if($a->hasMethod($this->action)){
			
		}
	}

	public function setController($controllerName)
	{
		$this->controller = $controllerName;
		return $this;
	}

	public function setParams(?array $params)
	{
		
	}
	public static function newInstance() {
		return (new ReflectionClass(Controller::class))->newInstance();
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Devoir::Ancestors()
	 */
	protected function Ancestors():array
	{
		$parent = parent::Ancestors();
		array_push($parent, static::class);
		return $parent;
	}
}

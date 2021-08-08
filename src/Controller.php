<?php
namespace Devoir;

use \ReflectionClass;
use Devoir\Exception\MissingControllerException;
use Devoir\Exception\MissingActionException;

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
	protected $basePath = BASE_PATH;
	protected $controllerParams = array();
	protected $viewVars = array();
	protected $urlType = URL_TYPE_SLASH;

	/**
	 * 
	 * @param mixed $controller
	 * @param string $action
	 * @param array $params
	 */
	public function __construct($controller = null, $action = null, ?array $params = array())
	{
		if(is_string($controller)){
			$this->setController($controller);
		}
		elseif(is_array($controller) && !empty($controller)){
			if(array_key_exists('controller', $controller)){
				$this->setController($controller['controller']);
			}
			elseif(array_key_exists('Controller', $controller)){
				$this->setController($controller['Controller']);
			}
			else{
				@list($controller, $action, $params) = $controller;
				$this->setController($controller);
			}
			if(array_key_exists('action', $controller)){
				$this->setAction($controller['action']);
			}
			elseif(array_key_exists('Action', $controller)){
				$this->setAction($controller['Action']);
			}
			else{
				@list($controller, $action, $params) = $controller;
				$this->setAction($action);
			}
			if(array_key_exists('params', $controller)){
				$this->setParams($controller['params']);
			}
			elseif(array_key_exists('Params', $controller)){
				$this->setParams($controller['Params']);
			}
			else{
				@list($controller, $action, $params) = $controller;
				$this->setParams($params);
			}
		}
		if(!is_null($action)){
			$this->setAction($action);
		}
		if(is_array($params) && !empty($params)){
			$this->setParams($params);
		}
	}
	public function init(){
		$this->parseURI();
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
		if($pos = strpos($controllerName, 'Controller')){
			$controllerName = substr($controllerName, 0, $pos);
		}
		$controllerName = ucfirst(strtolower($controllerName)) . "Controller";
		if(file_exists($this->basePath . $controllerName . '.php')){
			echo $this->basePath . $controllerName . '.php';
		}
		if (!class_exists($this->basePath . $controllerName . '.php')) {
			throw new MissingControllerException([$controllerName]);
		}
		$this->controller = $controllerName;
		return $this;
	}

	public function setParams(?array $params)
	{
		
	}
	public static function newInstance($controller = null, $action = null, ?array $params = array()) {
		return (new ReflectionClass(Controller::class))->newInstanceArgs([$controller, $action, $params]);
	}
	protected function parseURI(){
		$path = "";
		if($this->urlType == URL_TYPE_SLASH){
			$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
		}
		if($this->urlType == URL_TYPE_QUERY){
			$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY), "/");
		}
		$path = preg_replace('/[^a-zA-Z0-9]\//', "", $path);
		if (strpos($path, $this->basePath) === 0) {
			$path = substr($path, strlen($this->basePath));
		}
		echo $path;
		@list($controller, $action, $params) = explode('/', $path, 3);
		if(isset($controller)){
			$this->setController($controller);
		}
		if(isset($action)){
			$this->setAction($action);
		}
		else{
			$this->setAction(DEFAULT_ACTION);
		}
		if(isset($params)){
			$this->setParams(explode("/", $params));
		}
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Devoir::Ancestors()
	 */
	protected function Ancestors():array
	{
		$parent = parent::Ancestors();
		array_push($parent, Controller::class);
		return $parent;
	}
	/**
	 * 
	 * @return array
	 */
	public function getAncestors():array
	{
		$ancest = $this->Ancestors();
		array_pop($ancest);
		return $ancest;
	}
}
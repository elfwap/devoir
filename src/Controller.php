<?php
namespace Devoir;

use \ReflectionClass;
use Devoir\Exception\MissingControllerException;
use Devoir\Exception\MissingActionException;
use Devoir\Interfaces\ControllerInterface;
use Devoir\Interfaces\ControllerEventInterface;

/**
 *
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
class Controller extends Devoir implements ControllerInterface, ControllerEventInterface
{
	/**
	 * 
	 * @var string
	 */
	protected $controller = DEFAULT_CONTROLLER;
	/**
	 * Fully-qualified controller's name
	 * @var string
	 */
	private $fqController = "Devoir\\Controller\\" . DEFAULT_CONTROLLER;
	/**
	 * 
	 * @var string
	 */
	protected $action = DEFAULT_ACTION;
	/**
	 * 
	 * @var string
	 */
	protected $basePath = BASE_PATH;
	/**
	 * 
	 * @var array
	 */
	protected $controllerParams = array();
	/**
	 * 
	 * @var array
	 */
	protected $viewVars = array();
	/**
	 * 
	 * @var string
	 */
	protected $urlType = URL_TYPE_SLASH;

	/**
	 * 
	 * @param mixed $controller
	 * @param string $action
	 * @param array $params
	 */
	public function __construct($controller = null, $action = null, ?array $params = array())
	{
		if(is_string($controller) && !empty($controller)){
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
				@list($controllerx, $actionx, $paramsx) = $controller;
				$this->setController($controllerx);
			}
			
			if(array_key_exists('action', $controller)){
				$this->setAction($controller['action']);
			}
			elseif(array_key_exists('Action', $controller)){
				$this->setAction($controller['Action']);
			}
			else{
				@list($controllerx, $actionx, $paramsx) = $controller;
				$this->setAction($actionx);
			}
			
			if(array_key_exists('params', $controller)){
				$this->setParams($controller['params']);
			}
			elseif(array_key_exists('Params', $controller)){
				$this->setParams($controller['Params']);
			}
			else{
				@list($controllerx, $actionx, $paramsx) = $controller;
				$this->setParams($paramsx);
			}
		}
		if(!is_null($action) && !empty($action)){
			$this->setAction($action);
		}
		if(is_array($params) && !empty($params)){
			$this->setParams($params);
		}
	}
	/**
	 * @return null
	 */
	public function init(){
		if($this->controller == DEFAULT_CONTROLLER){
			$this->parseURI();
		}
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Devoir::__destruct()
	 */
	function __destruct()
	{
		$this->terminate();
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::setAction()
	 */
	public function setAction($actionName)
	{
		if(class_exists($this->fqController)){
			throw new MissingActionException([$actionName, $this->controller, "Method not defined"]);
			$reflect = new ReflectionClass($this->fqController);
			if(!$reflect->hasMethod($actionName)){
				throw new MissingActionException([$actionName, $this->controller, "Method not defined"]);
			}
			$this->action = $actionName;
		}
		else{
			throw new MissingControllerException([$this->controller, "Class [" . $this->fqController . "] not found"]);
		}
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::setViewVar()
	 */
	public function setViewVar($var)
	{
		$this->viewVars[] = $var;
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::getViewVars()
	 */
	public function getViewVars()
	{
		return $this->viewVars;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::run()
	 */
	public function run()
	{
		
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::setController()
	 */
	public function setController($controllerName)
	{
		if ($pos = strpos($controllerName, 'Controller')) {
			$controllerName = substr($controllerName, 0, $pos);
		}
		$controllerName = ucfirst(strtolower($controllerName)) . "Controller";
		$filename = rtrim(CONTROLLERS_PATH, DS) . DS . $controllerName . '.php';
		if (!file_exists($filename)) {
			throw new MissingControllerException([$controllerName, "File [" . $filename . "] not found"]);
		}
		$classname = trim(APPLICATION_NAMESPACE, '\\') . '\\Controllers\\' . $controllerName;
		if (!class_exists($classname)) {
			throw new MissingControllerException([$controllerName, "Class [" . $classname . "] not found"]);
		}
		else {
			$this->fqController = $classname;
		}
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::setParams()
	 */
	public function setParams(?array $params)
	{
		$this->controllerParams = $params;
		return $this;
	}
	public static function newInstance($controller = null, $action = null, ?array $params = array()) {
		return (new ReflectionClass(Controller::class))->newInstanceArgs([$controller, $action, $params]);
	}
	/**
	 * Parses URI to generate objects for Controllers and Action methods.
	 * @return null
	 */
	protected function parseURI(){
		$path = "";
		if($this->urlType == URL_TYPE_SLASH){
			$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
		}
		if($this->urlType == URL_TYPE_QUERY){
			$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY), "/");
			if(strpos($path, 'q=') === 0 || strpos($path,  '&q=') > 0){
				if(strpos($path,  '&q=') > 0){
					$path = substr($path, strpos($path,  '&q=') + 1);
				}
				if(strpos($path, '&') > 0) $path = explode('&', $path)[0];
				$path = explode('=', $path)[1];
			}
		}
		$path = preg_replace('/[^a-zA-Z0-9]\//', "", $path);
		if (strpos($path, $this->basePath) === 0) {
			$path = substr($path, strlen($this->basePath));
		}
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
	 * returns list of super classes that {$this} class extends
	 * @return array
	 */
	public function getAncestors():array
	{
		$ancest = $this->Ancestors();
		array_pop($ancest);
		return $ancest;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::beforeRunUp()
	 */
	public function beforeRunUp()
	{}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::afterRunUp()
	 */
	public function afterRunUp()
	{}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::afterDispatch()
	 */
	public function afterDispatch()
	{}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::beforeDispatch()
	 */
	public function beforeDispatch()
	{}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::initialize()
	 */
	public function initialize()
	{}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::terminate()
	 */
	public function terminate()
	{}

}
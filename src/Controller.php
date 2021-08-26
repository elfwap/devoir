<?php
namespace Devoir;

use \ReflectionClass;
use \ReflectionFunction;
use \Closure;
use Devoir\Exception\MissingControllerException;
use Devoir\Exception\MissingActionException;
use Devoir\Interfaces\ControllerInterface;
use Devoir\Interfaces\ControllerEventInterface;
use Devoir\Interfaces\DevoirEventInterface;
use Devoir\Exception\EventListenerException;
use Devoir\Exception\MissingInheritanceException;

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
	private $fqController = CONTROLLERS_NAMESPACE . DEFAULT_CONTROLLER;
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
	 * @var array
	 */
	protected array $_eventListeners = array();
	/**
	 * 
	 * @var boolean
	 */
	private bool $stoppedPropagation = false;
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
		$this->initialize();
	}
	/**
	 * @return null
	 */
	protected final function initialize(){
		$this->registerListener(EVENT_ON_INITIALIZE, EVENT_ON_INITIALIZE);
		$this->registerListener(EVENT_ON_TERMINATE, EVENT_ON_TERMINATE);
		$this->registerListener(EVENT_CONTROLLER_BEFORE_RUNUP, EVENT_CONTROLLER_BEFORE_RUNUP);
		$this->registerListener(EVENT_CONTROLLER_AFTER_RUNUP, EVENT_CONTROLLER_AFTER_RUNUP);
		$this->registerListener(EVENT_CONTROLLER_BEFORE_DISPATCH, EVENT_CONTROLLER_BEFORE_DISPATCH);
		$this->registerListener(EVENT_CONTROLLER_AFTER_DISPATCH, EVENT_CONTROLLER_AFTER_DISPATCH);
		if($this->controller == DEFAULT_CONTROLLER){
			$this->parseURI();
		}
		if(class_exists($this->fqController)){
			$this->dispatchEvent(EVENT_ON_INITIALIZE);
		}
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::setAction()
	 */
	public function setAction($actionName)
	{
		if($this->fqController == CONTROLLERS_NAMESPACE . DEFAULT_CONTROLLER && $this->controller == DEFAULT_CONTROLLER){
			throw new MissingControllerException([$this->controller, "URI not resolved"]);
		}
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
		$this->dispatchEvent(EVENT_CONTROLLER_BEFORE_RUNUP);
		// TODO: run up
		$this->dispatchEvent(EVENT_CONTROLLER_AFTER_RUNUP);
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
		$classname = CONTROLLERS_NAMESPACE . $controllerName;
		if (!file_exists($filename)) {
			throw new MissingControllerException([$controllerName, "File [" . $filename . "] not found"]);
		}
		if (!class_exists($classname)) {
			throw new MissingControllerException([$controllerName, "Class [" . $classname . "] not found"]);
		}
		else {
			$this->controller = $controllerName;
			$this->fqController = $classname;
		}
		if(!isController($classname)){
			throw new MissingInheritanceException([$classname, self::class]);
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
	public static final function newInstance($controller = null, $action = null, ?array $params = array()) {
		return (new ReflectionClass(self::class))->newInstanceArgs([$controller, $action, $params]);
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
		array_push($parent, self::class);
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
	 * @see \Devoir\Interfaces\ControllerEventInterface::onInitialize()
	 */
	public function onInitialize(DevoirEventInterface $event)
	{
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\DevoirEventInterface::onTerminate()
	 */
	public function onTerminate(DevoirEventInterface $event)
	{
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\DevoirEventInterface::isPropagationStopped()
	 */
	public final function isPropagationStopped(): bool
	{
		return $this->stoppedPropagation;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\DevoirEventInterface::registerListener()
	 */
	public final function registerListener($event, $callback, $object = null)
	{
		$this->_eventListeners[$event][] = ['callback' => $callback, 'object' => $object];
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\DevoirEventInterface::getListenersForEvent()
	 */
	public final function getListenersForEvent($event): iterable
	{
		return $this->_eventListeners[$event] ?? [];
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\DevoirEventInterface::dispatchEvent()
	 */
	public final function dispatchEvent($event)
	{
		$listeners = $this->getListenersForEvent($event);
		$exceptions = array();
		foreach ($listeners as $listener) {
			if(is_string($listener['callback']) && is_null($listener['object'])){
				if(!in_array($listener['callback'], $this->getImplementedListeners())){
					$exceptions[] = [$event, $listener['callback'], $this->fqController, "Callback not Implemented"];
				}
				if(class_exists($this->fqController)){
					$reflect = new ReflectionClass($this->fqController);
					if(!$reflect->hasMethod($listener['callback'])){
						$exceptions[] = [$event, $listener['callback'], $this->fqController, "Callback function not found"];
					}
					else{
						call_user_func_array([$this->fqController, $listener['callback']], [$this]);
					}
				}
				else{
					$exceptions[] = [$event, $listener['callback'], $this->fqController, "Class not found"];
				}
			}
			elseif(is_callable($listener['callback']) && is_null($listener['object'])){
				$reflect = new ReflectionFunction($listener['callback']);
				$reflect->invokeArgs([$this]);
			}
			elseif(is_callable($listener['callback']) && (is_object($listener['object']) || is_string($listener['object']))){
				$closure = Closure::fromCallable($listener['callback']);
				if(is_object($listener['object'])) $closure->call($listener['object'], $this);
				elseif(is_string($listener['object'])){
					if($listener['object'] == self::class){
						$reflect = new ReflectionFunction($listener['callback']);
						$exceptions[] = [$event, $reflect->getName(), self::class, "Cannot use `" . self::class . "` as closure class."];
					}
					else{
						$closure->call((new $listener['object']), $this);
					}
				}
			}
			elseif(is_string($listener['callback']) && (is_object($listener['object']) || is_string($listener['object']))){
				$reflect = new ReflectionClass($listener['object']);
				if(!in_array($listener['callback'], $this->getImplementedListeners())){
					$exceptions[] = [$event, $listener['callback'], $reflect->getName(), "Callback not Implemented"];
				}
				if(class_exists($reflect->getName())){
					if(!$reflect->hasMethod($listener['callback'])){
						$exceptions[] = [$event, $listener['callback'], $reflect->getName(), "Callback function not found"];
					}
					else{
						call_user_func_array([$listener['object'], $listener['callback']], [$this]);
					}
				}
				else{
					$exceptions[] = [$event, $listener['callback'], $reflect->getName(), "Class not found"];
				}
			}
			if($this->isPropagationStopped()) break;
		}
		if(count($exceptions) == 1){
			throw new EventListenerException($exceptions[0]);
		}
		elseif(count($exceptions) > 1){
			throw new EventListenerException([$exceptions, true]);
		}
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::beforeRunUp()
	 */
	public function beforeRunUp(ControllerEventInterface $event)
	{}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\DevoirEventInterface::getImplementedListeners()
	 */
	public function getImplementedListeners(): iterable
	{
		return [
			EVENT_ON_INITIALIZE,
			EVENT_ON_TERMINATE,
			EVENT_CONTROLLER_BEFORE_RUNUP,
			EVENT_CONTROLLER_AFTER_RUNUP,
			EVENT_CONTROLLER_BEFORE_DISPATCH,
			EVENT_CONTROLLER_AFTER_DISPATCH,
			EVENT_CONTROLLER_BEFORE_MANIFEST,
			EVENT_CONTROLLER_AFTER_MANIFEST
		];
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::afterRunUp()
	 */
	public function afterRunUp(ControllerEventInterface $event)
	{}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::afterDispatch()
	 */
	public function afterDispatch(ControllerEventInterface $event)
	{}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::beforeDispatch()
	 */
	public function beforeDispatch(ControllerEventInterface $event)
	{}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::beforeManifest()
	 */
	public function beforeManifest(ControllerEventInterface $event)
	{}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::afterManifest()
	 */
	public function afterManifest(ControllerEventInterface $event)
	{}
	protected final function terminate(): void
	{
		$this->dispatchEvent(EVENT_ON_TERMINATE);
		$this->stoppedPropagation = true;
	}
}
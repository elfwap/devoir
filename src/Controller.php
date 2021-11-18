<?php

namespace Devoir;

use \ReflectionClass;
use \ReflectionFunction;
use \ReflectionMethod;
use \Closure;
use \ArgumentCountError;
use \TypeError;
use Devoir\Exception\MissingControllerException;
use Devoir\Exception\MissingActionException;
use Devoir\Interfaces\ControllerInterface;
use Devoir\Interfaces\ControllerEventInterface;
use Devoir\Interfaces\DevoirEventInterface;
use Devoir\Exception\EventListenerException;
use Devoir\Exception\MissingInheritanceException;
use Devoir\Exception\NotFoundException;
use Devoir\Exception\BadRequestException;
use Devoir\Interfaces\ResponseInterface;
use Devoir\Exception\DevoirException;
use Devoir\Exception\Client4XXException;
use Devoir\Exception\Server5XXException;
use Devoir\Exception\Redirect3XXException;
use Devoir\Interfaces\RequestInterface;
use \stdClass;

/**
 * Front and main controller that every other controller inherits (extends).
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc. <https://github.com/elfwap>
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *
 */
class Controller extends Devoir implements ControllerInterface, ControllerEventInterface, ResponseInterface
{
	/**
	 * Controller's name. (`Not the Front controller`).
	 * @var string
	 */
	protected string $controller;
	/**
	 * Fully-qualified controller's name (`With namespace`).
	 * @var string 
	 */
	private string $fullyQualifiedController;
	/**
	 * Action's name (`Default's index`).
	 * @var string
	 */
	protected string $action;
	/**
	 * Relative path to web public path 
	 * (`or public path`) where the application is installed.
	 * @var string
	 */
	protected $basePath;
	/**
	 *
	 * @var array
	 */
	protected $actionParams = array();
	/**
	 *
	 * @var array
	 */
	protected $viewVars = array();
	/**
	 *
	 * @var int
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
	 * @var string
	 */
	private string $path;
	/**
	 * Response interface object.
	 * @var object
	 */
	protected ResponseInterface $response;
	/**
	 *
	 * @var integer
	 */
	protected int $response_status_code = 0;
	/**
	 *
	 * @var string
	 */
	protected string $response_location = "";
	/**
	 *
	 * @var string
	 */
	protected string $response_message = "";
	/**
	 * 
	 * @var Devoir\Configuration $config store loaded configuration data for entire runtime.
	 */
	protected Configuration $config;
	/**
	 * 
	 * @var \stdClass $app
	 */
	private stdClass $app;
	/**
	 * 
	 * @var \stdClass $appController
	 */
	private stdClass $appController;
	/**
	 * 
	 * @var \stdClass $appModel
	 */
	private stdClass $appModel;
	/**
	 * 
	 * @var \stdClass $appView
	 */
	private stdClass $appView;
	/**
	 *
	 * @param mixed $controller
	 * @param string $action
	 * @param array $params
	 */
	final public function __construct($controller = null, $action = null, array $params = array(), $systemDir = null)
	{
		$this->config = new Configuration($systemDir);
		$this->app = $this->config->get('app');
		$this->appController = $this->config->get('app', 'controller');
		$this->appModel = $this->config->get('app', 'model');
		$this->appView = $this->config->get('app', 'view');
		$this->controller = $this->app->default_controller;
		$this->fullyQualifiedController = $this->appController->namespace . $this->app->default_controller;
		$this->action = $this->app->default_action;
		$this->basePath = $this->app->base_path;
		/*
		repair this
		$this->path = $this->getHost();
		*/
		if (is_string($controller) && !empty($controller)) {
			$this->path .= '/' . $controller;
			$this->setController($controller);
		} elseif (is_array($controller) && !empty($controller)) {
			$cnt = 1;
			foreach ($controller as $value) {
				if(array_key_exists('controller', $controller)){
					$this->path .= '/' . $controller['controller'];
					$this->setController($controller['controller']);
				}
				elseif(array_key_exists('Controller', $controller)){
					$this->path .= '/' . $controller['Controller'];
					$this->setController($controller['Controller']);
				}
				elseif($cnt === 1){
					$this->path .= '/' . $value;
					$this->setController($value);
				}
	
				if(array_key_exists('action', $controller)){
					$this->path .= '/' . $controller['action'];
					$this->setAction($controller['action']);
				}
				elseif(array_key_exists('Action', $controller)){
					$this->path .= '/' . $controller['Action'];
					$this->setAction($controller['Action']);
				}
				elseif($cnt === 2){
					$this->path .= '/' . $value;
					$this->setAction($value);
				}
			
				if(array_key_exists('params', $controller)){
					if(!is_array($prms = $controller['params'])) $controller['params'] = [$prms];
					$this->path .= '/' . implode('/', $controller['params']);
					$this->setParams($controller['params']);
				}
				elseif(array_key_exists('param', $controller)){
					if(!is_array($prms = $controller['param'])) $controller['param'] = [$prms];
					$this->path .= '/' . implode('/', $controller['param']);
					$this->setParams($controller['param']);
				}
				elseif(array_key_exists('Params', $controller)){
					if(!is_array($prms = $controller['Params'])) $controller['Params'] = [$prms];
					$this->path .= '/' . implode('/', $controller['Params']);
					$this->setParams($controller['Params']);
				}
				elseif(array_key_exists('Param', $controller)){
					if(!is_array($prms = $controller['Param'])) $controller['Param'] = [$prms];
					$this->path .= '/' . implode('/', $controller['Param']);
					$this->setParams($controller['Param']);
				}
				elseif($cnt === 3){
					$this->path .= '/' . implode('/', $value);
					$this->setParams($value);
				}

				$cnt += 1;
			}
		}
		if (!isNull($action) && !empty($action)) {
			$this->path .= '/' . $action;
			$this->setAction($action);
		}
		if (is_array($params) && !empty($params)) {
			$this->path . '/' . implode('/', $params);
			$this->setParams($params);
		}
		$this->initialize();
	}
	/**
	 * @return null
	 */
	final protected function initialize()
	{
		$this->registerListener(EVENT_ON_INITIALIZE, EVENT_ON_INITIALIZE);
		$this->registerListener(EVENT_ON_TERMINATE, EVENT_ON_TERMINATE);
		$this->registerListener(EVENT_CONTROLLER_BEFORE_RUNUP, EVENT_CONTROLLER_BEFORE_RUNUP);
		$this->registerListener(EVENT_CONTROLLER_AFTER_RUNUP, EVENT_CONTROLLER_AFTER_RUNUP);
		$this->registerListener(EVENT_CONTROLLER_BEFORE_DISPATCH, EVENT_CONTROLLER_BEFORE_DISPATCH);
		$this->registerListener(EVENT_CONTROLLER_AFTER_DISPATCH, EVENT_CONTROLLER_AFTER_DISPATCH);
		if ($this->controller == $this->app->default_controller) {
			$this->parseURI();
		}
		if (class_exists($this->fullyQualifiedController)) {
			$this->dispatchEvent(EVENT_ON_INITIALIZE);
		}
	}

	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::setViewVar()
	 */
	final public function setViewVar($var)
	{
		$this->viewVars[] = $var;
		return $this;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::getViewVars()
	 */
	final public function getViewVars()
	{
		return $this->viewVars;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::run()
	 */
	final public function run()
	{
		try {
			$this->dispatchEvent(EVENT_CONTROLLER_BEFORE_RUNUP);
			if (in_array($this->action, (array) $this->getImplementedListeners())) {
				if(!$this->config->get('is_debug')) throw new NotFoundException([$this->path]);
				throw new BadRequestException(['Method `' . $this->action . '` is an event listener method, only invoked when specific event occurs. Avoid using any of the following methods: ' . implode(', ', (array) $this->getImplementedListeners())]);
			}
			$reflect = new ReflectionClass($this->fullyQualifiedController);
			$reflectm = new ReflectionMethod($this->fullyQualifiedController . '::' . $this->action);
			$runner = $reflectm->invokeArgs($reflect->newInstanceWithoutConstructor(), $this->actionParams);
			if ($runner instanceof ResponseInterface) {
				$this->response = $runner;
				if ($this->response->isRedirect()) {
					throw new Redirect3XXException([$this->response->getLocation(), $this->response->getStatusCode()]);
				}
				if ($this->response->isClientError()) {
					throw new Client4XXException([$this->response->getMessage(), $this->response->getStatusCode()]);
				}
				if ($this->response->isServerError()) {
					throw new Server5XXException([$this->response->getMessage(), $this->response->getStatusCode()]);
				}
			}
			//TODO - dispatch request to model
			//TODO render view
			$this->dispatchEvent(EVENT_CONTROLLER_AFTER_RUNUP);
		} catch (ArgumentCountError $acerr) {
			if(!$this->config->get('is_debug')) throw new BadRequestException();
			throw new DevoirException('Argument Count Error: ' . $reflectm->getNumberOfRequiredParameters() . ' Argument(s) required, ' . count($this->actionParams) . ' Supplied. on line [' . $acerr->getLine() . '] in file (' . explode(DS, $acerr->getFile())[count(explode(DS, $acerr->getFile())) - 1] . ')');
		}
		catch (TypeError $tperr) {
			if(!$this->config->get('is_debug')) throw new BadRequestException();
			throw new DevoirException($tperr->getMessage() . '. on line [' . $tperr->getLine() . ']. in file (' . explode(DS, $tperr->getFile())[count(explode(DS, $tperr->getFile())) - 1] . ')');
		}
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::setController()
	 */
	final public function setController($controllerName)
	{
		if ($pos = strpos($controllerName, 'Controller')) {
			$controllerName = substr($controllerName, 0, $pos);
		}
		$controllerName = ucfirst(strtolower($controllerName)) . "Controller";
		$controllerName = $this->_dashedToCamelCase($controllerName);
		$filename = rtrim($this->appController->path, DS) . DS . $controllerName . '.php';
		$classname = $this->appController->namespace . $controllerName;
		if (!file_exists($filename)) {
			if ($this->config->get('is_debug')) {
				throw new MissingControllerException([$controllerName, "File [" . $filename . "] not found"]);
			}
			throw new NotFoundException([$this->path]);
		}
		if (!class_exists($classname)) {
			throw new MissingControllerException([$controllerName, "Class [" . $classname . "] not found"]);
		} else {
			$this->controller = $controllerName;
			$this->fullyQualifiedController = $classname;
		}
		if (!isController($classname)) {
			throw new MissingInheritanceException([$classname, self::class]);
		}
		return $this;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::setAction()
	 */
	final public function setAction($actionName)
	{
		$actionName = $this->_dashedToCamelCase($actionName);
		if ($this->fullyQualifiedController == $this->config->get('app', 'controller.namespace') . $this->config->get('app', 'default_controller') && $this->config->get('app', 'default_controller')) {
			if ($this->config->get('is_debug')) {
				throw new MissingControllerException([$this->controller, "URI not resolved"]);
			}
			throw new BadRequestException(["URI not resolved"]);
		}
		if (class_exists($this->fullyQualifiedController)) {
			$reflect = new ReflectionClass($this->fullyQualifiedController);
			if (!$reflect->hasMethod($actionName)) {
				if ($this->config->get('is_debug')) {
					throw new MissingActionException([$actionName, $this->controller, "Method not defined"]);
				}
				throw new NotFoundException([$this->path]);
			}
			$this->action = $actionName;
		} else {
			if ($this->config->get('is_debug')) {
				throw new MissingControllerException([$this->controller, "Class [" . $this->fullyQualifiedController . "] not found"]);
			}
			throw new NotFoundException([$this->path]);
		}
		return $this;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::setParams()
	 */
	final public function setParams(?array $params)
	{
		$prms = array();
		foreach ($params as $key => $value) {
			$prms[$key] = $this->_dashedToCamelCase($value);
		}
		$this->actionParams = $prms;
		return $this;
	}
	/**
	 * Returns a new instance of this class as static object
	 *
	 * @return $this;
	 */
	final public static function newInstance($controller = null, $action = null, ?array $params = array())
	{
		return (new ReflectionClass(self::class))->newInstanceArgs([$controller, $action, $params]);
	}
	/**
	 * Parses URI to generate objects for Controllers and Action methods.
	 * @return null
	 */
	final protected function parseURI()
	{
		$path = "";
		if ($this->urlType == URL_TYPE_SLASH) {
			$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
		}
		if ($this->urlType == URL_TYPE_QUERY) {
			$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY), "/");
			if (strpos($path, 'devoir=') === 0 || strpos($path, '&devoir=') > 0) {
				if (strpos($path, '&devoir=') > 0) {
					$path = substr($path, strpos($path, '&devoir=') + 1);
				}
				if (strpos($path, '&') > 0) {
					$path = explode('&', $path)[0];
				}
				$path = explode('=', $path)[1];
			}
		}
		$path = preg_replace('/[^a-zA-Z0-9]\//', "", $path);
		if (strpos($path, $this->basePath . '/') === 0) {
			$path = substr($path, strlen($this->basePath . '/'));
		}
		$this->path .= $_SERVER["REQUEST_URI"];
		@list($controller, $action, $params) = explode('/', $path, 3);
		if (isset($controller)) {
			$this->setController($controller);
		}
		if (isset($action)) {
			$this->setAction($action);
		} else {
			$this->setAction($this->config->get('app', 'default_action'));
		}
		if (isset($params)) {
			$this->setParams(explode("/", $params));
		}
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Devoir::Ancestors()
	 */
	protected function Ancestors(): array
	{
		$parent = parent::Ancestors();
		array_push($parent, self::class);
		return $parent;
	}
	/**
	 * returns list of super classes that {$this} class extends
	 * @return array
	 */
	public function getAncestors(): array
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
	final public function isPropagationStopped(): bool
	{
		return $this->stoppedPropagation;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\DevoirEventInterface::registerListener()
	 */
	final public function registerListener($event, $callback, $object = null)
	{
		$this->_eventListeners[$event][] = ['callback' => $callback, 'object' => $object];
		return $this;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\DevoirEventInterface::getListenersForEvent()
	 */
	final public function getListenersForEvent($event): iterable
	{
		return $this->_eventListeners[$event] ?? [];
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\DevoirEventInterface::dispatchEvent()
	 */
	final public function dispatchEvent($event)
	{
		$listeners = $this->getListenersForEvent($event);
		$exceptions = array();
		foreach ($listeners as $listener) {
			if (is_string($listener['callback']) && isNull($listener['object'])) {
				if (!in_array($listener['callback'], (array) $this->getImplementedListeners())) {
					$exceptions[] = [$event, $listener['callback'], $this->fullyQualifiedController, "Callback not Implemented"];
				}
				if (class_exists($this->fullyQualifiedController)) {
					$reflect = new ReflectionClass($this->fullyQualifiedController);
					if (!$reflect->hasMethod($listener['callback'])) {
						$exceptions[] = [$event, $listener['callback'], $this->fullyQualifiedController, "Callback function not found"];
					} else {
						$reflectm = new ReflectionMethod($this->fullyQualifiedController . '::' . $listener['callback']);
						$reflectm->invokeArgs($reflect->newInstanceWithoutConstructor(), [$this]);
					}
				} else {
					$exceptions[] = [$event, $listener['callback'], $this->fullyQualifiedController, "Class not found"];
				}
			} elseif (is_callable($listener['callback']) && isNull($listener['object'])) {
				$reflect = new ReflectionFunction($listener['callback']);
				$reflect->invokeArgs([$this]);
			} elseif (is_callable($listener['callback']) && (is_object($listener['object']) || is_string($listener['object']))) {
				$closure = (new Closure())->fromCallable($listener['callback']);
				if (is_object($listener['object'])) {
					$closure->call($listener['object'], $this);
				} elseif (is_string($listener['object'])) {
					if ($listener['object'] == self::class) {
						$reflect = new ReflectionFunction($listener['callback']);
						$exceptions[] = [$event, $reflect->getName(), self::class, "Cannot use `" . self::class . "` as closure class."];
					} else {
						$closure->call((new $listener['object']), $this);
					}
				}
			} elseif (is_string($listener['callback']) && is_string($listener['object'])) {
				$reflect = new ReflectionClass($listener['object']);
				if (!in_array($listener['callback'], (array) $this->getImplementedListeners())) {
					$exceptions[] = [$event, $listener['callback'], $reflect->getName(), "Callback not Implemented"];
				}
				if (class_exists($reflect->getName())) {
					if (!$reflect->hasMethod($listener['callback'])) {
						$exceptions[] = [$event, $listener['callback'], $reflect->getName(), "Callback function not found"];
					} else {
						$reflectm = new ReflectionMethod($listener['object'] . '::' . $listener['callback']);
						$reflectm->invokeArgs($reflect->newInstanceWithoutConstructor(), [$this]);
					}
				} else {
					$exceptions[] = [$event, $listener['callback'], $reflect->getName(), "Class not found"];
				}
			}
			if ($this->isPropagationStopped()) {
				break;
			}
		}
		if (count($exceptions) == 1) {
			throw new EventListenerException($exceptions[0]);
		} elseif (count($exceptions) > 1) {
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
	{
	}
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
	{
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::afterDispatch()
	 */
	public function afterDispatch(ControllerEventInterface $event)
	{
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::beforeDispatch()
	 */
	public function beforeDispatch(ControllerEventInterface $event)
	{
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::beforeManifest()
	 */
	public function beforeManifest(ControllerEventInterface $event)
	{
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerEventInterface::afterManifest()
	 */
	public function afterManifest(ControllerEventInterface $event)
	{
	}
	/**
	 *
	 */
	final protected function terminate(): void
	{
		$this->dispatchEvent(EVENT_ON_TERMINATE);
		$this->stoppedPropagation = true;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ResponseInterface::redirectToLocation()
	 */
	public function redirectToLocation(?string $location, ?int $statusCode = RESPONSE_CODE_MOVED_TEMPORARILY): ResponseInterface
	{
		preg_match("(((ht|f)(tp)?s?://)?((\w)+\.)?(\w)+\.(\w){2,15}(\.(\w){2})?)", $location, $xd);
		if(count($xd) > 0) $this->setLocation($location);
		else $this->setLocation(strtolower(str_replace('//', '/', '/' . $location)));
		$this->setStatusCode($statusCode);
		return $this;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ResponseInterface::getLocation()
	 */
	public function getLocation(): string
	{
		return $this->response_location;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ResponseInterface::setStatusCode()
	 */
	public function setStatusCode(?int $code): ResponseInterface
	{
		$this->response_status_code = $code;
		return $this;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ResponseInterface::getResponse()
	 */
	public function getResponse(): iterable
	{
		return [
			"location" => $this->getLocation(),
			"code" => $this->getStatusCode(),
			"message" => $this->getMessage(),
			0 => $this->getLocation(),
			1 => $this->getStatusCode(),
			2 => $this->getMessage()
		];
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ResponseInterface::redirectToController()
	 */
	public function redirectToController(?array $uriArray, ?int $statusCode = RESPONSE_CODE_MOVED_TEMPORARILY): ResponseInterface
	{
		if(empty($uriArray)){
			throw new BadRequestException($this->config->get('is_debug') ? ["Redirecting to empty controller"] : null);
		}
		$this->setStatusCode($statusCode);
		$ctrl = "";
		$actn = "";
		$prms = "";
		$cnt = 1;
		foreach ($uriArray as $value) {
			if(array_key_exists('controller', $uriArray)) $ctrl = $uriArray['controller'];
			elseif(array_key_exists('Controller', $uriArray)) $ctrl = $uriArray['Controller'];
			elseif($cnt === 1) $ctrl = $value;

			if(array_key_exists('action', $uriArray)) $actn = $uriArray['action'];
			elseif(array_key_exists('Action', $uriArray)) $actn = $uriArray['Action'];
			elseif($cnt === 2) $actn = $value;

			if(array_key_exists('params', $uriArray)) $prms = $uriArray['params'];
			elseif(array_key_exists('Params', $uriArray)) $prms = $uriArray['Params'];
			elseif(array_key_exists('param', $uriArray)) $prms = $uriArray['param'];
			elseif(array_key_exists('Param', $uriArray)) $prms = $uriArray['Param'];
			elseif($cnt === 3) $prms = $value;

			$cnt += 1;
		}
		if(is_array($prms) && !empty($prms)){
			$prms = implode('/', $prms);
		}
		$ctrl = strtolower($ctrl);
		$actn = strtolower($actn);
		$this->setLocation('/' . implode('/', [$ctrl, $actn, $prms]));
		return $this;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ResponseInterface::getStatusCode()
	 */
	public function getStatusCode(): int
	{
		return $this->response_status_code;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ResponseInterface::redirectToAction()
	 */
	public function redirectToAction(?string $action, array $params = []): ResponseInterface
	{
		$this->setStatusCode(RESPONSE_CODE_MOVED_TEMPORARILY);
		$prms = (!empty($params)) ? implode('/', $params) : "";
		
		$ctrl = strtolower(str_replace('Controller', '', explode(DS, static::class)[count(explode(DS, static::class)) - 1]));
		$loc = [$ctrl, $action, $prms];
		$this->setLocation(strtolower(str_replace('//', '/', '/' . implode('/', $loc))));
		return $this;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ResponseInterface::getURI()
	 */
	public function getURI(): iterable
	{
		return [];
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ResponseInterface::isRedirect()
	 */
	public function isRedirect(): bool
	{
		return ($this->getStatusCode() > 299 && $this->getStatusCode() < 400) ? Yes : No;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ResponseInterface::isServerError()
	 */
	public function isServerError(): bool
	{
		return ($this->getStatusCode() > 499 && $this->getStatusCode() < 600) ? Yes : No;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ResponseInterface::isClientError()
	 */
	public function isClientError(): bool
	{
		return ($this->getStatusCode() > 399 && $this->getStatusCode() < 500) ? Yes : No;
	}
	public function setLocation(?string $location): ResponseInterface
	{
		$this->response_location = $location;
		return $this;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ResponseInterface::setURI()
	 */
	public function setURI(?iterable $uri): ResponseInterface
	{
		return $this;
	}
	private function _dashedToCamelCase(?string $value)
	{
		$comp = "";
		$varr = explode('-', $value);
		if (count($varr) < 2) {
			return $value;
		}
		$comp .= $varr[0];
		array_shift($varr);
		foreach ($varr as $vr) {
			$comp .= ucfirst($vr);
		}
		return $comp;
	}
	public function returnServerError(?string $message, ?int $statusCode = RESPONSE_CODE_INTERNAL_SERVER_ERROR): ResponseInterface
	{
		$this->response_message = $message;
		if (!($statusCode > 499) && !($statusCode < 600)) {
			throw new \InvalidArgumentException('Argument 1 (second) contains invalid value, Argument must be an integer with value of range [500 - 599]. ' . $statusCode . ' supplied');
		}
		$this->response_status_code = $statusCode;
		return $this;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ResponseInterface::returnClientError()
	 */
	public function returnClientError(?string $message, ?int $statusCode = RESPONSE_CODE_NOT_FOUND): ResponseInterface
	{
		$this->response_message = $message;
		if (!($statusCode > 399) && !($statusCode < 500)) {
			throw new \InvalidArgumentException('Argument 1 (second) contains invalid value, Argument must be an integer with value of range [400 - 499]. ' . $statusCode . ' supplied');
		}
		$this->response_status_code = $statusCode;
		return $this;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ResponseInterface::getMessage()
	 */
	public function getMessage(): string
	{
		return $this->response_message;
	}
	/**
	 *
	 * @param string $method
	 * @param array $args
	 */
	/* public function __call($method, $args){

	} */
}

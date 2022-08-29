<?php

namespace Devoir;

use \ReflectionClass;
use \ReflectionFunction;
use \ReflectionMethod;
use \Closure;
use \ArgumentCountError;
use \TypeError;
use Devoir\Exception\{MissingControllerException, MissingActionException, EventListenerException, MissingInheritanceException};
use Devoir\Interfaces\{ControllerInterface, DevoirEventInterface, ControllerEventInterface, ResponseInterface};
use Devoir\Exception\NotFoundException;
use Devoir\Exception\BadRequestException;
use Devoir\Exception\DevoirException;
use Devoir\Exception\{Client4XXException, Server5XXException, Redirect3XXException};
use \stdClass;

/**
 * Front and main controller that every other controller inherits (extends).
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) [2021 - 2022] Elftech Inc. <https://github.com/elfwap/devoir>
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *
 */
class Controller extends Devoir implements ControllerInterface, ControllerEventInterface
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
	 * @var array $stoppedPropagations
	 */
	private array $stoppedPropagations = [];
	/**
	 *
	 * @var string
	 */
	private string $path;
	/**
	 * 
	 * @var \Devoir\Configuration $config store loaded configuration data for current runtime.
	 */
	protected Configuration $config;
	/**
	 * 
	 * @var \stdClass $app
	 */
	private stdClass $app;
	/**
	 * Holds configuration details about `controller`
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
	 * @var BasicRequest $basic_request
	 */
	protected BasicRequest $basic_request;
	/**
	 * 
	 * @var BasicResponse $basic_response
	 */
	protected BasicResponse $basic_response;
	private $ctrl;
	private $actn;
	private $prms;
	private $sysd;
	/**
	* 
	* @var string $view_class
	* @var string $view_layout
	* @var string $view_frame
	* 
	*/
	private ?string $view_class = null, $view_frame = null, $view_layout = null;
	/**
	* 
	* @var View $view
	* 
	*/
	private View $view;
	/**
	 *
	 * @param mixed|null $controller
	 * @param mixed|null $action
	 * @param array $params
	 * @param mixed|null $systemDir
	 */
	final public function __construct($controller = null, $action = null, array $params = array(), $systemDir = null)
	{
		$this->ctrl = $controller;
		$this->actn = $action;
		$this->prms = $params;
		$this->sysd = $systemDir;
		$this->basic_response = new BasicResponse();
		$this->basic_request = new BasicRequest();
		$this->config = new Configuration($systemDir);
		$this->app = $this->config->get('app');
		$this->appController = $this->config->get('app', 'controller');
		$this->appModel = $this->config->get('app', 'model');
		$this->appView = $this->config->get('app', 'view');
		$this->controller = $this->app->default_controller;
		$this->fullyQualifiedController = $this->appController->namespace . $this->app->default_controller;
		$this->action = $this->app->default_action;
		$this->basePath = $this->app->base_path;
		$this->path = $this->basic_request->getHost('host');
		
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
		$this->registerListener(EVENT_ON_INITIALIZE, EVENT_ON_INITIALIZE, self::class);
		$this->registerListener(EVENT_ON_TERMINATE, EVENT_ON_TERMINATE);
		$this->registerListener(EVENT_ON_TERMINATE, EVENT_ON_TERMINATE, self::class);
		$this->registerListener(EVENT_CONTROLLER_BEFORE_RUNUP, EVENT_CONTROLLER_BEFORE_RUNUP);
		$this->registerListener(EVENT_CONTROLLER_BEFORE_RUNUP, EVENT_CONTROLLER_BEFORE_RUNUP, self::class);
		$this->registerListener(EVENT_CONTROLLER_AFTER_RUNUP, EVENT_CONTROLLER_AFTER_RUNUP);
		$this->registerListener(EVENT_CONTROLLER_AFTER_RUNUP, EVENT_CONTROLLER_AFTER_RUNUP, self::class);
		$this->registerListener(EVENT_CONTROLLER_BEFORE_DISPATCH, EVENT_CONTROLLER_BEFORE_DISPATCH);
		$this->registerListener(EVENT_CONTROLLER_BEFORE_DISPATCH, EVENT_CONTROLLER_BEFORE_DISPATCH, self::class);
		$this->registerListener(EVENT_CONTROLLER_AFTER_DISPATCH, EVENT_CONTROLLER_AFTER_DISPATCH);
		$this->registerListener(EVENT_CONTROLLER_AFTER_DISPATCH, EVENT_CONTROLLER_AFTER_DISPATCH, self::class);
		$this->registerListener(EVENT_CONTROLLER_BEFORE_MANIFEST, EVENT_CONTROLLER_BEFORE_MANIFEST);
		$this->registerListener(EVENT_CONTROLLER_BEFORE_MANIFEST, EVENT_CONTROLLER_BEFORE_MANIFEST, self::class);
		$this->registerListener(EVENT_CONTROLLER_AFTER_MANIFEST, EVENT_CONTROLLER_AFTER_MANIFEST);
		$this->registerListener(EVENT_CONTROLLER_AFTER_MANIFEST, EVENT_CONTROLLER_AFTER_MANIFEST, self::class);
		if ($this->controller == $this->app->default_controller) {
			$this->parseURI();
		}
		if (class_exists($this->fullyQualifiedController)) {
			foreach (debug_backtrace() as $dbb) {
			if ($dbb['function'] == "run") {
					$this->dispatchEvent(EVENT_ON_INITIALIZE);
				}
			}
		}
		$this->view_class = getConfig('app', 'default_view_class');
		$this->view_frame = getConfig('app', 'default_view_frame');
		$this->view_layout = getConfig('app', 'default_view_layout');
	}

	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::setViewVar()
	 */
	final public function setViewVar($var_name, $var_value = null): ControllerInterface
	{
		
		if (is_array($var_name)) {
			foreach ($var_name as $vk => $vn) {
				$this->viewVars[$vk] = $vn;
			}
			//$this->view->exportVars($var_name);
		}else {
			$this->viewVars[$var_name] = $var_value;
			//$this->view->exportVars($this->viewVars);
		}
		return $this;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::getViewVars()
	 */
	final public function getViewVars(): iterable
	{
		return $this->viewVars;
	}
	/**
	 *
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::run()
	 */
	final public function run(): void
	{
		try {
			$this->dispatchEvent(EVENT_CONTROLLER_BEFORE_RUNUP);
			if (in_array($this->action, (array) $this->getImplementedListeners())) {
				if(!$this->config->get('is_debug')) throw new NotFoundException([$this->path]);
				throw new BadRequestException(['Method `' . $this->action . '` is an event listener method, only invoked when specific event occurs. Avoid using any of the following methods: ' . implode(', ', (array) $this->getImplementedListeners())]);
			}
			$reflect = new ReflectionClass($this->fullyQualifiedController);
			$reflectm = new ReflectionMethod($this->fullyQualifiedController . '::' . $this->action);
			$runner = $reflectm->invokeArgs($reflect->newInstanceArgs([$this->ctrl, $this->actn, $this->prms, $this->sysd]), $this->actionParams);
			if ($runner instanceof ResponseInterface) {
				if ($runner->isRedirect()) {
					throw new Redirect3XXException([$runner->getLocation(), $runner->getStatusCode()]);
				}
				if ($runner->isClientError()) {
					throw new Client4XXException([$runner->getMessage(), $runner->getStatusCode()]);
				}
				if ($runner->isServerError()) {
					throw new Server5XXException([$runner->getMessage(), $runner->getStatusCode()]);
				}
			}
			//TODO - dispatch request to model
			if ($runner instanceof ControllerInterface) {
				$this->view = new View($runner, $this->view_class, null, null);
				$this->view->setLayout($this->view_layout);
				$this->view->setFrame($this->view_frame);
				$this->view->exportVars($runner->getViewVars());
				$this->view->render();
			}
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
	final public function setController($controllerName): ControllerInterface
	{
		if ($pos = strpos($controllerName, 'Controller')) {
			$controllerName = substr($controllerName, 0, $pos);
		}
		$controllerName = ucfirst(strtolower($controllerName)) . "Controller";
		$controllerName = $this->_dashedToCamelCase($controllerName);
		$filename = rtrim($this->appController->path, DS) . DS . $controllerName . '.php';
		$classname = $this->appController->namespace . $controllerName;
		if (!file_exists($filename)) {
			if (getConfig('is_debug')) {
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
	final public function setAction($actionName): ControllerInterface
	{
		$actionName = $this->_dashedToCamelCase($actionName);
		if ($this->fullyQualifiedController == $this->config->get('app', 'controller.namespace') . $this->config->get('app', 'default_controller') && $this->config->get('app', 'default_controller')) {
			if (getConfig('is_debug')) {
				throw new MissingControllerException([$this->controller, "URI not resolved"]);
			}
			throw new BadRequestException(["URI not resolved"]);
		}
		if (class_exists($this->fullyQualifiedController)) {
			$reflect = new ReflectionClass($this->fullyQualifiedController);
			if (!$reflect->hasMethod($actionName)) {
				if (getConfig('is_debug')) {
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
	final public function setParams(?array $params): ControllerInterface
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
			$path = trim(parse_url($this->basic_request->getServer('REQUEST_URI'), PHP_URL_PATH), "/");
		}
		if ($this->urlType == URL_TYPE_QUERY) {
			$path = trim(parse_url($this->basic_request->getServer('REQUEST_URI'), PHP_URL_QUERY), "/");
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
		$this->path .= $this->basic_request->getServer('REQUEST_URI');
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
	 * @see \Devoir\Interfaces\DevoirEventInterface::onInitialize()
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
	final public function isPropagationStopped($event = null): bool
	{
		if (isNull($event)) {
			if(array_key_exists('all', $this->stoppedPropagations)) return $this->stoppedPropagations['all'];
		}
		if (array_key_exists($event, $this->stoppedPropagations)) return $this->stoppedPropagations[$event];
		return NO;
	}
	/**
	* 
	* {@inheritdoc}
	* @see \Devoir\Interfaces\DevoirEventInterface::consumeEvent()
	*/
	final public function consumeEvent($event = null)
	{
		if (isNull($event)){
			$this->stoppedPropagations["all"] = true;
		}
		else{
			$this->stoppedPropagations[$event] = true;
		}
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
			if ($this->isPropagationStopped($event) || $this->isPropagationStopped()) {
				return $this;
			}
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
	 * Terminates the current Controller running.
	 */
	final protected function terminate(): void
	{
		$this->dispatchEvent(EVENT_ON_TERMINATE);
		die();
	}
	/**
	* 
	* {@inheritdoc}
	* @see \Devoir\Interfaces\ControllerInterface::setView()
	*/
	public function setView(?string $view_frame = null, ?string $view_layout = null, ?string $view_class = null): ControllerInterface
	{
		if ($view_class <> "ignore"){
			$this->view_class = $view_class ?? getConfig('app', 'default_view_class');
		}
		if ($view_frame <> "ignore"){
			$this->view_frame = $view_frame ?? getConfig('app', 'default_view_frame');
		}
		if ($view_layout <> "ignore"){
			$this->view_layout = $view_layout ?? getConfig('app', 'default_view_layout');
		}
		return $this;
	}
	
	public function getView(): iterable
	{
		return [
			'class' => $this->view_class,
			'layout' => $this->view_layout,
			'frame' => $this->view_frame
		];
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::setConfig()
	 */
	final public function setConfig($key, $value, $subkeys = null): ControllerInterface
	{
		$this->config = $this->config->set($key, $value, $subkeys);
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::getConfig()
	 */
	final public function getConfigData($key, $subkeys = null)
	{
		return $this->config->get($key, $subkeys);
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ControllerInterface::getConfig()
	 */
	final public function getConfig(): Configuration
	{
		return $this->config;
	}
	/**
	 *
	 * @param string $method
	 * @param array $args
	 */
	/* public function __call($method, $args){

	} */
}

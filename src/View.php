<?php

namespace Devoir;

use Devoir\Configuration;
use Devoir\Interfaces\{ViewEventInterface, ControllerEventInterface, ControllerInterface, ViewInterface, DevoirEventInterface};
use Devoir\Exception\{MissingViewClassException, MissingViewFrameException, MissingViewLayoutException, NotFoundException, MissingInheritanceException, EventListenerException};
use \ReflectionClass;
use \ReflectionFunction;
use \ReflectionMethod;
use \Closure;

/**
 * Main View class.
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) [2022] Elftech Inc. <https://github.com/elfwap/devoir>
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *
 */
class View extends Devoir implements ViewEventInterface, ViewInterface
{
	/**
	* 
	* @var $controller Controller event callback
	* 
	*/
	protected ControllerInterface $controller;
	/**
	 *
	 * @var array
	 */
	protected array $_eventListeners = array();
	/**
	 *
	 * @var array
	 */
	private array $stoppedPropagations = [];
	/**
	* 
	* @var string $viewClass Holds the View Classname, where view events and so could be implemented in
	* @var string $viewLayout Holds the name to the first part of the view to be rendered
	* @var string $viewFrame Hold the name to the second part of the view to be rendered, will be rendered in view layout
	* @var string $fullyQualifiedView Holds the path of the Fully qualified view class (namespaced).
	* 
	*/
	private ?string $viewClass = "", $viewLayout = "", $viewFrame = "", $viewTitle = "", $fullyQualifiedView = "";
	
	private ?string $viewLayoutFile, $viewFrameFile;
	/**
	* 
	* @var array $exported_vars
	* 
	*/
	private array $exported_vars = [];
	/**
	 * 
	 * @var \Devoir\Configuration $config store loaded configuration data for current runtime.
	 */
	private Configuration $config;
	
	public function __construct(ControllerInterface $controller_callback, ?string $view_class = null, ?string $view_layout = null, ?string $view_frame = null)
	{
		$this->controller = $controller_callback;
		$this->config = &$controller_callback->config;
		if (!isNull($view_class)) $this->setClass($view_class);
		if (!isNull($view_layout)) $this->setLayout($view_layout);
		if (!isNull($view_frame)) $this->setFrame($view_frame);
		$this->initialize();
	}
	final protected function initialize()
	{
		$this->registerListener(EVENT_ON_INITIALIZE, EVENT_ON_INITIALIZE);
		$this->registerListener(EVENT_ON_TERMINATE, EVENT_ON_TERMINATE);
		$this->registerListener(EVENT_VIEW_BEFORE_RENDER, EVENT_VIEW_BEFORE_RENDER);
		$this->registerListener(EVENT_VIEW_BEFORE_LAYOUT,EVENT_VIEW_BEFORE_LAYOUT);
		$this->registerListener(EVENT_VIEW_BEFORE_FRAME, EVENT_VIEW_BEFORE_FRAME);
		$this->registerListener(EVENT_VIEW_AFTER_FRAME, EVENT_VIEW_AFTER_FRAME);
		$this->registerListener(EVENT_VIEW_AFTER_LAYOUT, EVENT_VIEW_AFTER_LAYOUT);
		$this->registerListener(EVENT_VIEW_AFTER_RENDER, EVENT_VIEW_AFTER_RENDER);
		//self
		$this->registerListener(EVENT_ON_INITIALIZE, EVENT_ON_INITIALIZE, self::class);
		$this->registerListener(EVENT_ON_TERMINATE, EVENT_ON_TERMINATE, self::class);
		$this->registerListener(EVENT_VIEW_BEFORE_RENDER, EVENT_VIEW_BEFORE_RENDER, self::class);
		$this->registerListener(EVENT_VIEW_BEFORE_LAYOUT,EVENT_VIEW_BEFORE_LAYOUT, self::class);
		$this->registerListener(EVENT_VIEW_BEFORE_FRAME, EVENT_VIEW_BEFORE_FRAME, self::class);
		$this->registerListener(EVENT_VIEW_AFTER_FRAME, EVENT_VIEW_AFTER_FRAME, self::class);
		$this->registerListener(EVENT_VIEW_AFTER_LAYOUT, EVENT_VIEW_AFTER_LAYOUT, self::class);
		$this->registerListener(EVENT_VIEW_AFTER_RENDER, EVENT_VIEW_AFTER_RENDER, self::class);
		$this->dispatchEvent(EVENT_ON_INITIALIZE);
	}
	final protected function terminate()
	{
		$this->dispatchEvent(EVENT_ON_TERMINATE);
		die();
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ViewInterface::exportVars()
	 */
	public function exportVars(array $var_array)
	{
		$this->exported_vars = array_merge($this->exported_vars, $var_array);
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ViewInterface::exportedVars()
	 */
	public function exportedVars(): iterable
	{
		return $this->exported_vars;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ViewEventInterface::beforeRender()
	 */
 	public function beforeRender(ViewEventInterface $event)
 	{
 	}
 	/**
 	 * 
 	 * {@inheritDoc}
 	 * @see \Devoir\Interfaces\ViewEventInterface::afterRender()
 	 */
 	public function afterRender(ViewEventInterface $event)
 	{}
 	/**
 	 * 
 	 * {@inheritDoc}
 	 * @see \Devoir\Interfaces\ViewEventInterface::beforeLayout()
 	 */
 	public function beforeLayout(ViewEventInterface $event)
 	{}
 	/**
 	 * 
 	 * {@inheritDoc}
 	 * @see \Devoir\Interfaces\ViewEventInterface::afterLayout()
 	 */
 	public function afterLayout(ViewEventInterface $event)
 	{}
 	/**
 	 * 
 	 * {@inheritDoc}
 	 * @see \Devoir\Interfaces\ViewEventInterface::beforeFrame()
 	 */
 	public function beforeFrame(ViewEventInterface $event)
 	{}
 	/**
 	 * 
 	 * {@inheritDoc}
 	 * @see \Devoir\Interfaces\ViewEventInterface::afterFrame()
 	 */
 	public function afterFrame(ViewEventInterface $event)
 	{}
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
			if ($this->isPropagationStopped()) {
				break;
			}
			if (is_string($listener['callback']) && isNull($listener['object'])) {
				if (!in_array($listener['callback'], (array) $this->getImplementedListeners())) {
					$exceptions[] = [$event, $listener['callback'], $this->fullyQualifiedView, "Callback not Implemented"];
				}
				if (class_exists($this->fullyQualifiedView)) {
					$reflect = new ReflectionClass($this->fullyQualifiedView);
					if (!$reflect->hasMethod($listener['callback'])) {
						$exceptions[] = [$event, $listener['callback'], $this->fullyQualifiedView, "Callback function not found"];
					} else {
						$reflectm = new ReflectionMethod($this->fullyQualifiedView . '::' . $listener['callback']);
						$reflectm->invokeArgs($reflect->newInstanceWithoutConstructor(), [$this]);
					}
				} else {
					$exceptions[] = [$event, $listener['callback'], $this->fullyQualifiedView, "Class not found"];
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
	 * @see \Devoir\Interfaces\DevoirEventInterface::getImplementedListeners()
	 */
	public function getImplementedListeners(): iterable
	{
		return [
			EVENT_ON_INITIALIZE,
			EVENT_ON_TERMINATE,
			EVENT_VIEW_BEFORE_RENDER,
			EVENT_VIEW_AFTER_RENDER,
			EVENT_VIEW_BEFORE_LAYOUT,
			EVENT_VIEW_AFTER_LAYOUT,
			EVENT_VIEW_BEFORE_FRAME,
			EVENT_VIEW_AFTER_FRAME
		];
	}
	/**
	* 
	* {@inheritdoc}
	* @see \Devoir\Interfaces\ViewInterface::setClass()
	*/
	final public function setClass(string $class_name)
	{
		if ($pos = strpos($class_name, 'View')) {
			$class_name = substr($class_name, 0, $pos);
		}
		$class_name = ucfirst(strtolower($class_name)) . "View";
		$class_name = $this->_dashedToCamelCase($class_name);
		$filename = rtrim($this->getConfigData('app', 'view.path'), DS) . DS . 'Classes' . DS . $class_name . '.php';
		$classname = $this->getConfigData('app', 'view.namespace') . $class_name;
		if (!file_exists($filename)) {
			if ($this->getConfigData('is_debug')) {
				throw new MissingViewClassException([$class_name, "File [" . $filename . "] not found"]);
			}
			throw new NotFoundException(['View `' . $class_name . '`']);
		}
		if (!class_exists($classname)) {
			throw new MissingViewClassException([$class_name, "Class [" . $classname . "] not found"]);
		} else {
			$this->viewClass = $class_name;
			$this->fullyQualifiedView = $classname;
		}
		if (!isView($classname)) {
			throw new MissingInheritanceException([$classname, self::class]);
		}
		return $this;
	}
	/**
	* 
	* {@inheritdoc}
	* @see \Devoir\Interfaces\ViewInterface::getClass()
	*/
	final public function getClass(): string
	{
		return $this->viewClass;
	}
	/**
	* 
	* {@inheritdoc}
	* @see \Devoir\Interfaces\ViewInterface::setLayout()
	*/
	final public function setLayout(string $name)
	{
		$layout_name = null;
		if ($pos = strpos($name, '_layout')) {
			$layout_name = substr($name, 0, $pos);
		}
		$layout_name .= "_layout";
		$filename = rtrim($this->getConfigData('app', 'view.path'), DS) . DS . 'Layout' . DS . $layout_name . '.php';
		if (!file_exists($filename)) {
			if ($this->getConfigData('is_debug')) {
				throw new MissingViewLayoutException([$layout_name, "File [" . $filename . "] not found"]);
			}
			throw new NotFoundException(['View layout file `' . $layout_name . '`']);
		}
		$this->viewLayout = $layout_name;
		$this->viewLayoutFile = $filename;
		return $this;
	}
	/**
	* 
	* {@inheritdoc}
	* @see \Devoir\Interfaces\ViewInterface::getLayout()
	*/
	final public function getLayout(): string
	{
		return $this->viewLayout;
	}
	/**
	* 
	* {@inheritdoc}
	* @see \Devoir\Interfaces\ViewInterface::setFrame()
	*/
	final public function setFrame(string $name)
	{
		$frame_name = null;
		if ($pos = strpos($name, '_frame')) {
			$frame_name = substr($name, 0, $pos);
		}
		$frame_name .= "_frame";
		$filename = rtrim($this->getConfigData('app', 'view.path'), DS) . DS . 'Frame' . DS . $frame_name . '.php';
		if (!file_exists($filename)) {
			if ($this->getConfigData('is_debug')) {
				throw new MissingViewFrameException([$frame_name, "File [" . $filename . "] not found"]);
			}
			throw new NotFoundException(['View frame file `' . $frame_name . '`']);
		}
		$this->viewFrame = $name;
		$this->viewFrameFile = $filename;
		return $this;
	}
	/**
	* 
	* {@inheritdoc}
	* @see \Devoir\Interfaces\ViewInterface::getFrame()
	*/
	final public function getFrame(): string
	{
		return $this->viewFrame;
	}
	/**
	* 
	* {@inheritDoc}
	* @see \Devoir\Interfaces\ViewInterface::setTitle()
	*/
	final public function setTitle(string $text = "untitled")
	{
		$this->viewTitle = $text;
		return $this;
	}
	/**
	* 
	* {@inheritDoc}
	* @see \Devoir\Interfaces\ViewInterface::getTitle()
	*/
	final public function getTitle(): string
	{
		return $this->viewTitle;
	}
	final public function render(): void
	{
		$this->controller->dispatchEvent(EVENT_CONTROLLER_BEFORE_MANIFEST);
		$this->dispatchEvent(EVENT_VIEW_BEFORE_RENDER);
		$this->dispatchEvent(EVENT_VIEW_BEFORE_LAYOUT);
		ob_start();
		extract($this->exported_vars);
		require_once($this->viewLayoutFile);
		$layout = ob_get_contents();
		ob_end_clean();
		if (strpos($layout, '{{TITLE}}') >= 0) $layout = str_replace('{{TITLE}}', $this->viewTitle, $layout);
		(strpos($layout, '{{FRAME}}') >= 0) ? $layers = explode('{{FRAME}}', $layout) : $layers = [$layout, " "];
		if (count($layers) >= 2) echo($layers[0]);
		$this->dispatchEvent(EVENT_VIEW_BEFORE_FRAME);
		include_once($this->viewFrameFile);
		$this->dispatchEvent(EVENT_VIEW_AFTER_FRAME);
		if (count($layers) >= 2) echo($layers[1]);
		$this->dispatchEvent(EVENT_VIEW_AFTER_LAYOUT);
		$this->dispatchEvent(EVENT_VIEW_AFTER_RENDER);
		$this->controller->dispatchEvent(EVENT_CONTROLLER_AFTER_MANIFEST);
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ViewInterface::setConfigData()
	 */
	final public function setConfigData($key, $value, ?string $subkeys = null): ViewInterface
	{
		$this->config = $this->config->set($key, $value, $subkeys);
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ViewInterface::getConfigData()
	 */
	final public function getConfigData($key, $subkeys = null)
	{
		return $this->config->get($key, $subkeys);
	}
	/**
	* 
	* {@inheritDoc}
	* @see \Devoir\Interfaces\ViewInterface::setConfig()
	*/
	final public function setConfig(Configuration $config) 
	{
		$this->config = $config;
		$this->controller->config = &$this->config;
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see \Devoir\Interfaces\ViewInterface::getConfig()
	 */
	final public function getConfig(): Configuration
	{
		return $this->config;
	}
}

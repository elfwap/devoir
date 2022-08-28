<?php

namespace Devoir;

use Devoir\Interfaces\{ViewEventInterface, ControllerEventInterface, ControllerInterface, ViewInterface, DevoirEventInterface};
use Devoir\Exception\{MissingViewClassException, MissingViewFrameException, MissingViewLayoutException, NotFoundException, MissingInheritanceException};
use \ReflectionClass;
use \ReflectionFunction;
use  \ReflectionMethod;
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
	private ?string $viewClass, $viewLayout, $viewFrame, $fullyQualifiedView;
	
	private ?string $viewLayoutFile, $viewFrameFile;
	/**
	* 
	* @var array $exported_vars
	* 
	*/
	private array $exported_vars = [];
	/**
	 * 
	 * @var Devoir\Configuration $config store loaded configuration data for current runtime.
	 */
	protected Configuration $config;
	
	public function __construct()
	{
		$this->initialize();
	}
	public function setCallback(ControllerInterface $controller_callback)
	{
		$this->controller = $controller_callback;
	}
	public function initialize()
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
	 * {@inheritdoc}
	 * @see \Devoir\Intefaces\ViewEventInterface::beforeRender()
	 * 
	 */
 	public function beforeRender(ViewEventInterface $event)
 	{
 	}
 	/**
	 * {@inheritdoc}
	 * @see \Devoir\Intefaces\ViewEventInterface::afterRender()
	 * 
	 */
 	public function afterRender(ViewEventInterface $event)
 	{}
 	/**
	 * {@inheritdoc}
	 * @see \Devoir\Intefaces\ViewEventInterface::beforeLayout()
	 * 
	 */
 	public function beforeLayout(ViewEventInterface $event)
 	{}
 	/**
	 * {@inheritdoc}
	 * @see \Devoir\Intefaces\ViewEventInterface::afterLayout()
	 * 
	 */
 	public function afterLayout(ViewEventInterface $event)
 	{}
 	/**
	 * {@inheritdoc}
	 * @see \Devoir\Intefaces\ViewEventInterface::beforeFrame()
	 * 
	 */
 	public function beforeFrame(ViewEventInterface $event)
 	{}
 	/**
	 * {@inheritdoc}
	 * @see \Devoir\Intefaces\ViewEventInterface::afterFrame()
	 * 
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
	public function setClass(string $class_name)
	{
		if ($pos = strpos($class_name, 'View')) {
			$class_name = substr($class_name, 0, $pos);
		}
		$class_name = ucfirst(strtolower($class_name)) . "View";
		$class_name = $this->_dashedToCamelCase($class_name);
		$filename = rtrim(getConfig('app', 'view.path'), DS) . DS . 'Classes' . DS . $class_name . '.php';
		$classname = getConfig('app', 'view.namespace') . $class_name;
		if (!file_exists($filename)) {
			if (getConfig('is_debug')) {
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
	public function getClass(): string
	{
		return $this->viewClass;
	}
	/**
	* 
	* {@inheritdoc}
	* @see \Devoir\Interfaces\ViewInterface::setLayout()
	*/
	public function setLayout(string $name)
	{
		if ($pos = strpos($name, '_layout')) {
			$layout_name = substr($name, 0, $pos);
		}
		$layout_name .= "_layout";
		$filename = rtrim(getConfig('app', 'view.path'), DS) . DS . 'Layout' . DS . $layout_name . '.php';
		if (!file_exists($filename)) {
			if (getConfig('is_debug')) {
				throw new MissingViewLayoutException([$layout_name, "File [" . $filename . "] not found"]);
			}
			throw new NotFoundException(['View layout file `' . $class_name . '`']);
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
	public function getLayout(): string
	{
		return $this->viewLayout;
	}
	/**
	* 
	* {@inheritdoc}
	* @see \Devoir\Interfaces\ViewInterface::setFrame()
	*/
	public function setFrame(string $name)
	{
		if ($pos = strpos($name, '_frame')) {
			$frame_name = substr($name, 0, $pos);
		}
		$frame_name .= "_frame";
		$filename = rtrim(getConfig('app', 'view.path'), DS) . DS . 'Frame' . DS . $frame_name . '.php';
		if (!file_exists($filename)) {
			if (getConfig('is_debug')) {
				throw new MissingViewFrameException([$frame_name, "File [" . $filename . "] not found"]);
			}
			throw new NotFoundException(['View frame file `' . $class_name . '`']);
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
	public function getFrame(): string
	{
		return $this->viewFrame;
	}
	public function render()
	{
		$this->dispatchEvent(EVENT_VIEW_BEFORE_RENDER);
		$this->controller->dispatchEvent(EVENT_CONTROLLER_BEFORE_MANIFEST);
		ob_start();
		extract($this->exported_vars);
		require_once($this->viewLayoutFile);
		$layout = ob_get_contents();
		ob_end_clean();
		ob_start();
		include_once($this->viewFrameFile);
		$frame = ob_get_contents();
		ob_end_clean();
		$render = (strpos($layout, '{{FRAME}}') >= 0) ? str_replace('{{FRAME}}', $frame, $layout) : $layout;
		echo($render);
		$this->dispatchEvent(EVENT_VIEW_AFTER_RENDER);
		$this->controller->dispatchEvent(EVENT_CONTROLLER_AFTER_MANIFEST);
	}
}

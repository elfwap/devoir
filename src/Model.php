<?php
namespace Devoir;

use Devoir\Interfaces\ModelInterface;
use Devoir\Interfaces\ModelEventInterface;
use \ReflectionClass;
use \ReflectionFunction;
use \ReflectionMethod;
use \Closure;

/**
 * Main Model class.
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) [2022] Elftech Inc. <https://github.com/elfwap/devoir>
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *
 */
class Model extends Devoir implements ModelInterface, ModelEventInterface
{
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
	 * @var BasicRequest $basic_request
	 */
	protected BasicRequest $basic_request;
	/**
	 *
	 * @var BasicResponse $basic_response
	 */
	protected BasicResponse $basic_response;
	
	/**
	 */
	public function __construct()
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\ModelInterface::setViewVar()
	 */
	public function setViewVar($var_name, $var_value = null)
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\ModelInterface::getModel()
	 */
	public function getModel()
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\ModelInterface::dispatch()
	 */
	public function dispatch()
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\ModelInterface::setConfigData()
	 */
	public function setConfigData($key, $value, $subkeys = null)
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\ModelInterface::getViewVars()
	 */
	public function getViewVars()
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\ModelInterface::setModel()
	 */
	public function setModel($modelName)
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\ModelInterface::getConfigData()
	 */
	public function getConfigData($key, $subkeys = null)
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\ModelInterface::getConfig()
	 */
	public function getConfig()
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\ModelInterface::setConfig()
	 */
	public function setConfig(Configuration $config)
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\DevoirEventInterface::getImplementedListeners()
	 */
	public function getImplementedListeners()
	{
		return [
			EVENT_ON_INITIALIZE,
			EVENT_ON_TERMINATE,
			EVENT_MODEL_BEFORE_SAVE,
			EVENT_MODEL_AFTER_SAVE,
			EVENT_MODEL_BEFORE_DISPATCH,
			EVENT_MODEL_AFTER_DISPATCH
		];
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\DevoirEventInterface::isPropagationStopped()
	 */
	public function isPropagationStopped($event = null)
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\ModelEventInterface::afterDispatch()
	 */
	public function afterDispatch(ModelEventInterface $event)
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\DevoirEventInterface::dispatchEvent()
	 */
	public function dispatchEvent($event)
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\ModelEventInterface::afterSave()
	 */
	public function afterSave(ModelEventInterface $event)
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\DevoirEventInterface::onInitialize()
	 */
	public function onInitialize(DevoirEventInterface $event)
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\DevoirEventInterface::onTerminate()
	 */
	public function onTerminate(DevoirEventInterface $event)
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\ModelEventInterface::beforeDispatch()
	 */
	public function beforeDispatch(ModelEventInterface $event)
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
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
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\ModelEventInterface::beforeSave()
	 */
	public function beforeSave(ModelEventInterface $event)
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\DevoirEventInterface::getListenersForEvent()
	 */
	final public function getListenersForEvent($event): iterable
	{
		return $this->_eventListeners[$event] ?? [];
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\DevoirEventInterface::registerListener()
	 */
	final public function registerListener($event, $callback, $object = null)
	{
		$this->_eventListeners[$event][] = ['callback' => $callback, 'object' => $object];
		return $this;
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
	}
	/**
	 * Terminates the process from the current model.
	 */
	final protected function terminate(): void
	{
		$this->dispatchEvent(EVENT_ON_TERMINATE);
		die();
	}
}


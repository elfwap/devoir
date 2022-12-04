<?php
namespace Devoir;

use Devoir\Interfaces\ModelInterface;
use Devoir\Interfaces\ModelEventInterface;

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

		// TODO - Insert your code here
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
	public function consumeEvent($event = null)
	{

		// TODO - Insert your code here
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
	public function getListenersForEvent($event)
	{

		// TODO - Insert your code here
	}

	/**
	 * {@inheritDoc}
	 *
	 * @see \Devoir\Interfaces\DevoirEventInterface::registerListener()
	 */
	public function registerListener($event, $callback, $object = null)
	{

		// TODO - Insert your code here
	}
}


<?php
namespace Devoir\Interfaces;

/**
 * Model Event Interface, should be implemented by the Models.
 * @namespace Devoir\Interfaces
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
interface ModelEventInterface extends DevoirEventInterface
{
	/**
	 * Triggered before data is saved
	 * @param ModelEventInterface $event
	 */
	public function beforeSave(ModelEventInterface $event);
	/**
	 * Triggered after data is saved
	 * @param ModelEventInterface $event
	 */
	public function afterSave(ModelEventInterface $event);
	/**
	 * Triggered before the request data is being dispatched to the current model
	 * @param ModelEventInterface $event
	 */
	public function beforeDispatch(ModelEventInterface $event);
	/**
	 * Triggered after the request data is being dispatched to the current model
	 * @param ModelEventInterface $event
	 */
	public function afterDispatch(ModelEventInterface $event);
}


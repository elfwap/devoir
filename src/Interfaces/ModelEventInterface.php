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
	/**
	 * 
	 * @param ModelEventInterface $event
	 */
	public function afterFind(ModelEventInterface $event);
	/**
	 * 
	 * @param ModelEventInterface $event
	 */
	public function beforeInsert(ModelEventInterface $event);
	/**
	 * 
	 * @param ModelEventInterface $event
	 */
	public function afterInsert(ModelEventInterface $event);
	/**
	 * 
	 * @param ModelEventInterface $event
	 */
	public function beforeUpdate(ModelEventInterface $event);
	/**
	 * 
	 * @param ModelEventInterface $event
	 */
	public function afterUpdate(ModelEventInterface $event);
	/**
	 * 
	 * @param ModelEventInterface $event
	 */
	public function beforeDelete(ModelEventInterface $event);
	/**
	 * 
	 * @param ModelEventInterface $event
	 */
	public function afterDelete(ModelEventInterface $event);
}


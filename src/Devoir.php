<?php
namespace Devoir;

/**
 * Devoir class.
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */

class Devoir
{   
	/**
	 * Returns array of ancestral classes
	 * starting from the first super class to the immediate parent class
	 * @return array
	 */
	public function getAncestors($object_or_class = null):array
	{
		return array_reverse(class_parents($object_or_class ?? static::class));
	}
	/**
	 * Converts `dashed-value` to `camelCase`.
	 * @param mixed|null $value
	 * @return mixed
	 */
	protected function _dashedToCamelCase($value)
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
}

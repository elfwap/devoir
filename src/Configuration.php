<?php
namespace Devoir;

use \ReflectionObject;
use \stdClass;

/**
 *
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */

 class Configuration
 {
	 /**
	  * @var array $config store configuration data as array
	  */
	 private array $config;
	 /**
	  * @var object $configs store configuration data as object
	  */
	 private object $configs;

	 /**
	  * @param string|null $systemDir Framework's system directory where some meta files resides.
	  */
	public function __construct(string $systemDir = null)
	{
		$this->configs = new stdClass();
		if(is_dir($systemDir)){
			$configs = $systemDir . DIRECTORY_SEPARATOR . 'configs.php';
			$config = array();
			if(file_exists($configs)){
				$configs = require($configs);
				foreach ($configs as $key => $value) {
					$config[$key] = $value;
				}
			}
		}
		$devoirSystemDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'system';
		if(is_dir($devoirSystemDir)){
			$devoirConfigs = $devoirSystemDir . DIRECTORY_SEPARATOR . 'configs.php';
			$this->config = array();
			if(file_exists($devoirConfigs)){
				$devoirConfigs = require($devoirConfigs);
				foreach ($devoirConfigs as $key => $value) {
					$this->config[$key] = $value;
				}
				foreach ($config as $key => $value) {
					$this->config[$key] = $value;
				}
			}
		}
	}
	/**
	 * Sets a single configuration data during runtime.
	 * @param string $key
	 * @param mixed|object $value
	 * @param string|null $subkeys separated by `.` for lower elevation(s).
	 * @return Devoir\Configuration
	 */
	public function set(string $key, $value = null, string $subkeys = null){
		$jsonConfig = json_decode(json_encode($this->config));
		$this->configs->{$key} = &$jsonConfig->{$key};
		if(!isNull($subkeys) && !empty($subkeys)){
			$sk = explode('.', $subkeys);
			$ts = $key;
			foreach ($sk as $skey => $svalue) {
				$this->configs->{$svalue} = &$this->configs->{$ts}->{$svalue};
				$ts = $svalue;
				if($skey == (count($sk) - 1)){
					$this->configs->{$svalue} = $value;
				}
			}
		}
		else{
			$this->configs->{$key} = $value;
		}
		$this->configs = $jsonConfig;
		return $this;
	}
	/**
	 * Returns specified configuration data
	 * @param string $key
	 * @param string|null $subkeys separated by `.` for lower elevation(s).
	 * @return mixed|object
	 */
	public function get(string $key, string $subkeys = null){
		$ro = new ReflectionObject($this->configs);
		$rp = $ro->getProperties();
		$ret = null;
		if(count($rp) > 0){
			$jsonConfig = $this->configs;
		}
		else{
			$jsonConfig = json_decode(json_encode($this->config));
		}
		$this->configs->{$key} = &$jsonConfig->{$key};
		if(!isNull($subkeys) && !empty($subkeys)){
			$sk = explode('.', $subkeys);
			$ts = $key;
			foreach ($sk as $skey => $svalue) {
				$this->configs->{$svalue} = &$this->configs->{$ts}->{$svalue};
				$ts = $svalue;
				if($skey == (count($sk) - 1)){
					$ret = $this->configs->{$svalue};
				}
			}
		}
		else{
			$ret = $this->configs->{$key};
		}
		if(count($rp) < 1){
			$this->configs = $jsonConfig;
		}
		return $ret;
	}
	/**
	 * Returns configuration data as object or array or JSON string.
	 * @param int $type
	 * @return array|object|string|false
	 */
	public function getAll(int $type){
		$ret = null;
		if(count((new ReflectionObject($this->configs))->getProperties()) < 1){
			$this->configs = json_decode(json_encode($this->config));
		}
		switch ($type) {
			case CONFIG_AS_ARRAY:
				$ret = $this->config;
				break;
			case CONFIG_AS_OBJECT:
				$ret = $this->configs;
				break;
			case CONFIG_AS_JSON:
				$ret = json_encode($this->config);
				break;
			default:
				$ret = false;
				break;
		}
		return $ret;
	}
 }
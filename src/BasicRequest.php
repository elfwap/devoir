<?php
namespace Devoir;

use Devoir\Interfaces\RequestInterface;

/**
 * Basic Request class.
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc. <https://github.com/elfwap/devoir>
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *
 */
class BasicRequest extends Devoir implements RequestInterface
{
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isGet()
	 */
	public function isGet(): bool
	{
		return (strtolower($this->getServer('REQUEST_METHOD')) == 'get') ? Yes : No;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isPost()
	 */
	public function  isPost(): bool
	{
		return (strtolower($this->getServer('REQUEST_METHOD')) == 'post') ? Yes : No;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isPatch()
	 */
	public function isPatch(): bool
	{
		return (strtolower($this->getServer('REQUEST_METHOD')) == 'patch') ? Yes : No;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isDelete()
	 */
	public function isDelete(): bool
	{
		return (strtolower($this->getServer('REQUEST_METHOD')) == 'delete') ? Yes : No;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isPut()
	 */
	public function isPut(): bool
	{
		return (strtolower($this->getServer('REQUEST_METHOD')) == 'put') ? Yes : No;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isJSON()
	 */
	public function isJSON(): bool
	{
		// TODO: implement this method.
		return false;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterfaces::is()
	 */
	public function is($type): bool
	{
		$tarr = array();
		if (is_string($type)) {
			if (strpos($type, '|') > 0) {
				$tarr = explode('|', $type);
			}
			else {
				return (strtolower($this->getServer('REQUEST_METHOD')) == strtolower(trim($type))) ? Yes : No;
			}
		}
		if (is_array($type) && !empty($type)) {
			$tarr = $type;
		}
		foreach ($tarr as $value) {
			if (strtolower($this->getServer('REQUEST_METHOD')) == strtolower(trim($value))) {
				return Yes;
			}
		}
		return No;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getData()
	 */
	public function getData($data_key = null)
	{
		return $this->getPost($data_key);
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getHost()
	 */
	public function getHost(?string $which = null)
	{
		$ret = "";
		switch (strtolower($which)) {
			case ('name'):
				$ret = $this->getServer('SERVER_NAME');
				break;
			case ('addr'):
				$ret = $this->getServer('SERVER_ADDR');
				break;
			case ('address'):
				$ret = $this->getServer('SERVER_ADDR');
				break;
			case ('port'):
				$ret = $this->getServer('SERVER_PORT');
				break;
			case ('host'):
				$ret = $this->getServer('HTTP_HOST');
				break;
			case (null):
				$ret = [
					'name' => $this->getHost('name'),
					'address' => $this->getHost('addr'),
					'port' => $this->getHost('port'),
					'host' => $this->getHost('host')
				];
				break;
		}
		return $ret;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getPort()
	 */
	public function getPort(): int
	{
		return intval($this->getServer('SERVER_PORT'));
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getScheme()
	 */
	public function getScheme(): string
	{
		return strtoupper($this->getServer('REQUEST_SCHEME'));
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getPath()
	 */
	public function getPath(): string
	{
		$srv = $this->getServer('REQUEST_URI');
		$srv = explode('/', $srv, 2)[1];
		$srv = explode('?', $srv, 2)[0];
		$srv = '/' . $srv;
		return $srv;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getQuery()
	 */
	public function getQuery(?string $data = null)
	{
		$srv = $this->getServer('REQUEST_URI');
		$qarr = array();
		if(count($exp = explode('?', $srv)) === 2){
			$srv = $exp[1];
			if(count($exp = explode('&', $srv)) >= 1){
				foreach ($exp as $value) {
					$exp1 = explode('=', $value);
					$qarr[$exp1[0]] = $exp1[1];
				}
			}
		}
		if(!isNull($data)){
			if(array_key_exists($data, $qarr)) return $qarr[$data];
			else return false;
		}
		return $qarr;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getServer()
	 */
	public function getServer($index = null)
	{
		if(!isNull($index)){
			if(array_key_exists($index, $_SERVER)) return $_SERVER[$index];
			else return false;
		}
		return $_SERVER;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getGet()
	 */
	public function getGet($index = null)
	{
		if(!isNull($index)){
			if(array_key_exists($index, $_GET)) return $_GET[$index];
			else return false;
		}
		return $_GET;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getRequest()
	 */
	public function getRequest($index = null)
	{
		if(!isNull($index)){
			if(array_key_exists($index, $_REQUEST)) return $_REQUEST[$index];
			else return false;
		}
		return $_REQUEST;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getPost()
	 */
	public function getPost($index = null)
	{
		if(!isNull($index)){
			if(array_key_exists($index, $_POST)) return $_POST[$index];
			else return false;
		}
		return $_POST;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getCookies()
	 */
	public function getCookies($index = null)
	{
		if(!isNull($index)){
			if(array_key_exists($index, $_COOKIE)) return $_COOKIE[$index];
			else return false;
		}
		return $_COOKIE;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getSession()
	 */
	public function getSession($index = null)
	{
		$ret = false;
		switch (session_status()) {
			case PHP_SESSION_DISABLED:
				$ret = no;
			break;
			case PHP_SESSION_NONE:
				$ret = null;
			break;
			case PHP_SESSION_ACTIVE:
				if(!isNull($index)){
					if(array_key_exists($index, $_SESSION)) $ret = $_SESSION[$index];
					else $ret = no;
				}
				else {
					$ret = $_SESSION;
				}
			break;
		}
		return $ret;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getEnv()
	 */
	public function getEnv($index = null)
	{
		if(!isNull($index)){
			if(array_key_exists($index, (getenv() ?? $_ENV))) return getenv() ?? $_ENV[$index];
			else return false;
		}
		return getenv() ?? $_ENV;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getFiles()
	 */
	public function getFiles($index = null)
	{
		if(!isNull($index)){
			if(array_key_exists($index, $_FILES)) return $_FILES[$index];
			else return false;
		}
		return $_FILES;
	}
}

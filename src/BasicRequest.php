<?php
namespace Devoir;

use Devoir\Interfaces\RequestInterface;

/**
 * Basic Request class.
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc. <https://github.com/elfwap>
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
	public function isGet(RequestInterface $request): bool
	{
		return false;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isPost()
	 */
	public function  isPost(RequestInterface $request): bool
	{
		return false;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isPatch()
	 */
	public function isPatch(RequestInterface $request): bool
	{
		return false;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isDelete()
	 */
	public function isDelete(RequestInterface $request): bool
	{
		return true;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isPut()
	 */
	public function isPut(RequestInterface $request): bool
	{
		return true;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isJSON()
	 */
	public function isJSON(RequestInterface $request): bool
	{
		return false;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterfaces::is()
	 */
	public function is(?string $type, RequestInterface $request): bool
	{
		return false;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::setData()
	 */
	public function setData(?string $data, $value)
	{
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getData()
	 */
	public function getData(?string $data = null)
	{
		return "";
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getHost()
	 */
	public function getHost(): string
	{
		return "";
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getPort()
	 */
	public function getPort(): int
	{
		return 0;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::getScheme()
	 */
	public function getScheme(): string
	{
		return "";
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
	public function getServer(?string $index = null)
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
	 * @see \Devoir\Devoir::Ancestors()
	 */
	protected function Ancestors(): array
	{
		$parent = parent::Ancestors();
		array_push($parent, self::class);
		return $parent;
	}
	/**
	 * returns list of super classes that {$this} class extends
	 * @return array
	 */
	public function getAncestors(): array
	{
		$ancest = $this->Ancestors();
		array_pop($ancest);
		return $ancest;
	}
}

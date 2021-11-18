<?php
namespace Devoir\Interfaces;

/**
 * RequestInterface
 * @namespace Devoir\Interfaces
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
interface RequestInterface
{
	/**
	 * Checks whether the request is `GET`.
	 * @param Devoir\Interfaces\RequestInterface $request
	 * @return bool true|false
	 */
	public function isGet(RequestInterface $request): bool;
	/**
	 * Checks whether the request is `POST`.
	 * @param Devoir\Interfaces\RequestInterface $request
	 * @return bool true|false
	 */
	public function  isPost(RequestInterface $request): bool;
	/**
	 * Checks whether the request is `PATCH`.
	 * @param Devoir\Interfaces\RequestInterface $request
	 * @return bool true|false
	 */
	public function isPatch(RequestInterface $request): bool;
	/**
	 * Checks whether the request is `DELETE`.
	 * @param Devoir\Interfaces\RequestInterface $request
	 * @return bool true|false
	 */
	public function isDelete(RequestInterface $request): bool;
	/**
	 * Checks whether the request is `PUT`.
	 * @param Devoir\Interfaces\RequestInterface $request
	 * @return bool true|false
	 */
	public function isPut(RequestInterface $request): bool;
	/**
	 * Checks whether the request is `JSON`.
	 * @param Devoir\Interfaces\RequestInterface $request
	 * @return bool true|false
	 */
	public function isJSON(RequestInterface $request): bool;
	/**
	 * Checks whether the request is `GET|PUT|POST` and so on.
	 * @param string $type string value of type to check on.
	 * @param Devoir\Interfaces\RequestInterface $request
	 * @return bool true|false
	 */
	public function is(?string $type, RequestInterface $request):bool;
	/**
	 * Set the query data to post request before dispatch.
	 * @param string $data
	 * @param mixed|array $value
	 */
	public function setData(?string $data, $value);
	/**
	 * Get a single query data from post request
	 * @param string|null $data
	 */
	public function getData(?string $data = null);
	/**
	 * Get the host name of the requested address.
	 * @return string Host name as string value.
	 */
	public function getHost(): string;
	/**
	 * Get the port number of the requested address.
	 * @return int Port number as integer value.
	 */
	public function getPort(): int;
	/**
	 * Get URL Scheme for the current request.
	 * @return string
	 */
	public function getScheme(): string;
	/**
	 * 
	 * @return string
	 */
	public function getPath(): string;
	/**
	 * @param string|null $data
	 * @return string|array
	 */
	public function getQuery(?string $data = null);
	/**
	 * @param string|null $index
	 * @return mixed|array
	 */
	public function getServer(?string $index = null);
}

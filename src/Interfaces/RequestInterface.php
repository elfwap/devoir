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
	 * @return bool
	 */
	public function isGet(): bool;
	/**
	 * Checks whether the request is `POST`.
	 * @return bool
	 */
	public function  isPost(): bool;
	/**
	 * Checks whether the request is `PATCH`.
	 * @return bool
	 */
	public function isPatch(): bool;
	/**
	 * Checks whether the request is `DELETE`.
	 * @return bool
	 */
	public function isDelete(): bool;
	/**
	 * Checks whether the request is `PUT`.
	 * @return bool
	 */
	public function isPut(): bool;
	/**
	 * Checks whether the request is `JSON`.
	 * @return bool
	 */
	public function isJSON(): bool;
	/**
	 * Checks whether the request is `GET|PUT|POST` and so on.
	 * @param mixed|array $type mixed value for single or array to match many (`CASE-insensitive`).
	 * @return bool
	 * ```php
	 * $request->is('GET');
	 * $request->is('GET|POST');
	 * $request->is(['POST', 'PATCH']);
	 * ```
	 * 
	 */
	public function is($type):bool;
	/**
	 * Get a single or array of data from post request
	 * @param mixed|null $data_key Index to the array of data
	 * @return mixed|array
	 */
	public function getData($data_key = null);
	/**
	 * Get the host name, address and of the request.
	 * @param null|string $which
	 * @return mixed|array Host name as string value.
	 */
	public function getHost(?string $which = null);
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
	 * Returns path in the requested address
	 * @return string
	 */
	public function getPath(): string;
	/**
	 * Returns query as a singular string or array
	 * @param string|null $data
	 * @return string|array
	 */
	public function getQuery(?string $data = null);
	/**
	 * Returns a single entry from the `$_SERVER` variable if parameter is specified
	 * or the whole `$_SERVER` variable as array.
	 * @param mixed|null $index
	 * @return mixed|array
	 */
	public function getServer($index = null);
	/**
	 * Returns a single entry from the `$_GET` variable if parameter is specified
	 * or the whole `$_GET` variable as array.
	 * @param mixed|null $index
	 * @return mixed|array
	 */
	public function getGet($index = null);
	/**
	 * Returns a single entry from the `$_POST` variable if parameter is specified
	 * or the whole `$_POST` variable as array.
	 * @param mixed|null $index
	 * @return mixed|array
	 */
	public function getPost($index = null);
	/**
	 * Returns a single entry from the `$_REQUEST` variable if parameter is specified
	 * or the whole `$_REQUEST` variable as array.
	 * @param mixed|null $index
	 * @return mixed|array
	 */
	public function getRequest($index = null);
	/**
	 * Returns a single entry from the `$_COOKIE` variable if parameter is specified
	 * or the whole `$_COOKIES` variable as array.
	 * @param mixed|null $index
	 * @return mixed|array
	 */
	public function getCookies($index = null);
	/**
	 * Returns a single entry from the `$_SESSION` variable if parameter is specified
	 * or the whole `$_SESSION` variable as array.
	 * @param mixed|null $index
	 * @return mixed|array
	 */
	public function getSession($index = null);
	/**
	 * Returns a single entry from the `$_ENV` variable or `getenv()` if parameter is specified
	 * or the whole `$_ENV` variable or `getenv()` as array.
	 * @param mixed|null $index
	 * @return mixed|array
	 */
	public function getEnv($index = null);
	/**
	 * Returns a single entry from the `$_FILES` variable if parameter is specified
	 * or the whole `$_FILES` variable as array.
	 * @param mixed|null $index
	 * @return mixed|array
	 */
	public function getFiles($index = null);
}

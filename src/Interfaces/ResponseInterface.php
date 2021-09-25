<?php
namespace Devoir\Interfaces;

/**
 * ResponseInterface
 * @namespace Devoir\Interfaces
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *        
 */
interface ResponseInterface
{
	/**
	 * Redirects to URI Location with reason
	 * @param string $location
	 * @param int $statusCode
	 * @param string $reason
	 * @return ResponseInterface
	 */
	public function redirectToLocation(?string $location, ?int $statusCode = RESPONSE_CODE_MOVED_TEMPORARILY): ResponseInterface;
	/**
	 * 
	 * Redirect to another controller with different action and parameters with reason
	 * @param array $uriArray
	 * @param int $statusCode
	 * @param string $reason
	 * @return ResponseInterface
	 */
	public function redirectToController(?array $uriArray, ?int $statusCode = RESPONSE_CODE_MOVED_TEMPORARILY): ResponseInterface;
	/**
	 * 
	 * Redirect to another action in the same controller with reason
	 * @param string $action
	 * @param array $params
	 * @return ResponseInterface
	 */
	public function redirectToAction(?string $action, array $params = []): ResponseInterface;
	/**
	 * 
	 * Set response status code
	 * @param int $code
	 * @return ResponseInterface
	 */
	public function setStatusCode(?int $code): ResponseInterface;
	/**
	 * 
	 * Returns the integer value of the status code
	 * @return int
	 */
	public function getStatusCode(): int;
	/**
	 * Sets Location value
	 * @param string $location
	 * @return ResponseInterface
	 */
	public function setLocation(?string $location): ResponseInterface;
	/**
	 * 
	 * Returns string value of location
	 * @return string
	 */
	public function getLocation(): string;
	/**
	 * 
	 * @param iterable $uri
	 * @return ResponseInterface
	 */
	public function setURI(?iterable $uri): ResponseInterface;
	/**
	 * returns response message string
	 * @return string
	 */
	public function getMessage(): string;
	/**
	 * Returns the iterable (array or object) value of location
	 * @return iterable
	 */
	public function getURI(): iterable;
	/**
	 * Returns the iterable (array or object) value of the full response
	 * @return iterable
	 */
	public function getResponse(): iterable;
	/**
	 * Returns true if it's a 3XX status code
	 * @return bool
	 */
	public function isRedirect(): bool;
	/**
	 * Returns true if it's a 4XX status code
	 * @return bool
	 */
	public function isClientError(): bool;
	/**
	 * Returns true if it's a 500 status code
	 * @return bool
	 */
	public function isServerError(): bool;
	/**
	 * set 4XX Client error as response
	 * @param string $message
	 * @param int $statusCode
	 * @return ResponseInterface
	 */
	public function returnClientError(?string $message, ?int $statusCode = RESPONSE_CODE_NOT_FOUND): ResponseInterface;
	/**
	 * set 5XX Server error as response
	 * @param string $message
	 * @param int $statusCode
	 * @return ResponseInterface
	 */
	public function returnServerError(?string $message, ?int $statusCode = RESPONSE_CODE_INTERNAL_SERVER_ERROR): ResponseInterface;
}
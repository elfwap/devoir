<?php
namespace Devoir;

use Devoir\Interfaces\ResponseInterface;

class BasicResponse implements ResponseInterface
{
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::redirectToAction()
	 */
	public function redirectToAction(?string $action, array $params = []): ResponseInterface
	{
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::redirectToController()
	 */
	public function redirectToController(?array $uriArray, ?int $statusCode = RESPONSE_CODE_MOVED_TEMPORARILY): ResponseInterface
	{
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::redirectToLocation()
	 */
	public function redirectToLocation(?string $location, ?int $statusCode = RESPONSE_CODE_MOVED_TEMPORARILY): ResponseInterface
	{
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::returnClientError()
	 */
	public function returnClientError(?string $message, ?int $statusCode = RESPONSE_CODE_NOT_FOUND): ResponseInterface
	{
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::returnServerError()
	 */
	public function returnServerError(?string $message, ?int $statusCode = RESPONSE_CODE_INTERNAL_SERVER_ERROR): ResponseInterface
	{
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::setStatusCode()
	 */
	public function setStatusCode(?int $code): ResponseInterface
	{
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::getStatusCode()
	 */
	public function getStatusCode(): int
	{
		return 0;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::setLocation()
	 */
	public function setLocation(?string $location): ResponseInterface
	{
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::getLocation()
	 */
	public function getLocation(): string
	{
		return "";
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::setURI()
	 */
	public function setURI(?iterable $uri): ResponseInterface
	{
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::getURI()
	 */
	public function getURI(): iterable
	{
		return [];
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::getMessage()
	 */
	public function getMessage(): string
	{
		return "";
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::getResponse()
	 */
	public function getResponse(): iterable
	{
		return [];
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::isClientError()
	 */
	public function isClientError(): bool
	{
		return no;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::isServerError()
	 */
	public function isServerError(): bool
	{
		return no;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::isRedirect()
	 */
	public function isRedirect(): bool
	{
		return no;
	}
}

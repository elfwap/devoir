<?php
namespace Devoir;

use Devoir\Interfaces\ResponseInterface;
use Devoir\Exception\BadRequestException;

class BasicResponse extends Devoir implements ResponseInterface
{
	/**
	 * 
	 * @var string $response_message Holds the response message, incase of `client` or `server` error.
	 */
	protected string $response_message;
	/**
	 * 
	 * @var string $response_location Holds the string value of response location, incase of `http redirect`.
	 */
	protected string $response_location;
	/**
	 * 
	 * @var integer $response_status_code Holds the integer value of the `http response code`.
	 */
	protected int $response_status_code;
	/**
	 * 
	 * @var array $response_uri Holds the response URI in the form of
	 * ```php
	 * [
	 * 'Controller' => ...,
	 * 'action' => ...
	 * 'params' => [...]
	 * ]
	 * ```
	 */
	protected array $response_uri;
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::redirectToAction()
	 */
	public function redirectToAction(?string $action, array $params = []): ResponseInterface
	{
		$this->setStatusCode(RESPONSE_CODE_MOVED_TEMPORARILY);
		$prms = (!empty($params)) ? implode('/', $params) : "";
		$dgbt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT);
		$ctrl = strtolower(str_replace('Controller', '', explode(DS, $dgbt[1]['class'])[count(explode(DS, $dgbt[1]['class'])) - 1]));
		$loc = [$ctrl, $action, $prms];
		$this->response_uri = [$ctrl, $action, $params];
		$this->setLocation(strtolower(str_replace('//', '/', '/' . implode('/', $loc))));
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::redirectToController()
	 */
	public function redirectToController(?array $uriArray, ?int $statusCode = RESPONSE_CODE_MOVED_TEMPORARILY): ResponseInterface
	{
		if(empty($uriArray)){
			throw new BadRequestException($this->config->get('is_debug') ? ["Redirecting to empty controller"] : null);
		}
		$this->setStatusCode($statusCode);
		$ctrl = "";
		$actn = "";
		$prms = "";
		$cnt = 1;
		foreach ($uriArray as $value) {
			if(array_key_exists('controller', $uriArray)) $ctrl = $uriArray['controller'];
			elseif(array_key_exists('Controller', $uriArray)) $ctrl = $uriArray['Controller'];
			elseif($cnt === 1) $ctrl = $value;

			if(array_key_exists('action', $uriArray)) $actn = $uriArray['action'];
			elseif(array_key_exists('Action', $uriArray)) $actn = $uriArray['Action'];
			elseif($cnt === 2) $actn = $value;

			if(array_key_exists('params', $uriArray)) $prms = $uriArray['params'];
			elseif(array_key_exists('Params', $uriArray)) $prms = $uriArray['Params'];
			elseif(array_key_exists('param', $uriArray)) $prms = $uriArray['param'];
			elseif(array_key_exists('Param', $uriArray)) $prms = $uriArray['Param'];
			elseif($cnt === 3) $prms = $value;

			$cnt += 1;
		}
		if(is_array($prms) && !empty($prms)){
			$prms2 = $prms;
			$prms = implode('/', $prms);
		}
		$ctrl = strtolower($ctrl);
		$actn = strtolower($actn);
		$this->response_uri = [$ctrl, $actn, $prms2];
		$this->setLocation('/' . implode('/', [$ctrl, $actn, $prms]));
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::redirectToLocation()
	 */
	public function redirectToLocation(?string $location, ?int $statusCode = RESPONSE_CODE_MOVED_TEMPORARILY): ResponseInterface
	{
		preg_match("(((ht|f)(tp)?s?://)?((\w)+\.)?(\w)+\.(\w){2,15}(\.(\w){2})?)", $location, $xd);
		if (count($xd) > 0) $this->setLocation($location);
		else {
			$this->setLocation(strtolower(str_replace('//', '/', '/' . $location)));
			$lox = explode('/', strtolower(str_replace('//', '/', '/' . $location)), 3);
			if (is_array($lox[2]) && !empty($lox[2])) $this->response_uri = [$lox[0], $lox[1], explode('/', $lox[2])];
			else $this->response_uri = $lox;
		}
		$this->setStatusCode($statusCode);
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::returnClientError()
	 */
	public function returnClientError(?string $message, ?int $statusCode = RESPONSE_CODE_NOT_FOUND): ResponseInterface
	{
		$this->response_message = $message;
		if (!($statusCode > 399) && !($statusCode < 500)) {
			throw new \InvalidArgumentException('Argument 1 (second) contains invalid value, Argument must be an integer with value of range [400 - 499]. ' . $statusCode . ' supplied');
		}
		$this->response_status_code = $statusCode;
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::returnServerError()
	 */
	public function returnServerError(?string $message, ?int $statusCode = RESPONSE_CODE_INTERNAL_SERVER_ERROR): ResponseInterface
	{
		$this->response_message = $message;
		if (!($statusCode > 499) && !($statusCode < 600)) {
			throw new \InvalidArgumentException('Argument 1 (second) contains invalid value, Argument must be an integer with value of range [500 - 599]. ' . $statusCode . ' supplied');
		}
		$this->response_status_code = $statusCode;
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::setStatusCode()
	 */
	public function setStatusCode(?int $code): ResponseInterface
	{
		$this->response_status_code = $code;
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::getStatusCode()
	 */
	public function getStatusCode(): int
	{
		return $this->response_status_code;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::setLocation()
	 */
	public function setLocation(?string $location): ResponseInterface
	{
		$this->response_location = $location;
		return $this;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::getLocation()
	 */
	public function getLocation(): string
	{
		return $this->response_location;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::getURI()
	 */
	public function getURI(): iterable
	{
		return $this->response_uri;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::getMessage()
	 */
	public function getMessage(): string
	{
		return $this->response_message;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::getResponse()
	 */
	public function getResponse(): iterable
	{
		return [
			"location" => $this->getLocation(),
			"code" => $this->getStatusCode(),
			"message" => $this->getMessage(),
			0 => $this->getLocation(),
			1 => $this->getStatusCode(),
			2 => $this->getMessage()
		];
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::isClientError()
	 */
	public function isClientError(): bool
	{
		return ($this->getStatusCode() > 399 && $this->getStatusCode() < 500) ? yes : no;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::isServerError()
	 */
	public function isServerError(): bool
	{
		return ($this->getStatusCode() > 499 && $this->getStatusCode() < 600) ? Yes : no;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\ResponseInterface::isRedirect()
	 */
	public function isRedirect(): bool
	{
		return ($this->getStatusCode() > 299 && $this->getStatusCode() < 400) ? Yes : No;
	}
}

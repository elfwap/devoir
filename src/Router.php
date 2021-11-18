<?php
namespace Devoir;

use Devoir\Interfaces\RequestInterface;

/**
 * Router class. Used when creating an abstract routing to an existing controller without visiting it
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *
 */
class Router implements RequestInterface
{
	private array $route;
	private string $controller;
	private string $action;
	private array $params;
	public function __construct(string $systemDir = null)
	{
		if(is_dir($systemDir)){
			$routes = $systemDir . DIRECTORY_SEPARATOR . 'routes.php';
			$route = array();
			if(file_exists($routes)){
				$routes = require($routes);
				foreach ($routes as $key => $value) {
					$this->route[$key] = $value;
				}
			}
		}
		$devoirSystemDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'system';
		if(is_dir($devoirSystemDir)){
			$devoirRoutes = $devoirSystemDir . DIRECTORY_SEPARATOR . 'routes.php';
			$this->route = array();
			if(file_exists($devoirRoutes)){
				$devoirRoutes = require($devoirRoutes);
				foreach ($devoirRoutes as $key => $value) {
					if (array_key_exists($key, $this->route)) continue;
					$this->route[$key] = $value;
				}
			}
		}
	}
	/**
	 * Matches the current request against the route(s)
	 * Returns `TRUE` if matched else `FALSE`.
	 * @return bool
	 */
	public function match(): bool
	{
		$controller_found = no;
		$action_found = no;
		$params_found = no;
		$inc = 0;
		$keys = array();
		$ctrl = "";
		$actn = "";
		$prms = "";
		foreach ($this->route as $key => $value) {
			$exp = explode(';', $key);
			if(is_array($exp)) $exp = array_filter($exp);
			$pth = $this->getPath();
			$qry = $this->getQuery('devoir');
			$pthexp = explode('/', trim($pth, '/'), 3);
			$qryexp = [];
			if($qry <> false) $qryexp = explode('/', trim($qry, '/'), 3);
			if (is_array($pthexp)) $pthexp = array_filter($pthexp);
			if (is_array($qryexp)) $qryexp = array_filter($qryexp);
			if (empty($pthexp)) {
				$pthexp[0] = '/';
				$pthexp[1] = 'index';
			}
			if (count($pthexp) < 2) $pthexp[1] = 'index';
			$kys = array_filter(explode(';', $key));
			foreach ($kys as $kvalue) {
				if (strpos($kvalue, 'c:=') === 0) {
					if (
						((explode(':=', $kvalue)[1] == '%' && $pthexp[0] == '/') || 
						explode(':=', $kvalue)[1] == '~' || 
						explode(':=', $kvalue)[1] == $pthexp[0]) && $controller_found == NO
						) {
						$ctrl = $this->route[$key]['controller'];
						$controller_found = yes;
					}
					elseif (preg_match(REG_EXP_PICKUP_PATH, $kvalue, $matches_puc) === 1 && $controller_found == NO) {
						$keys[$matches_puc[1]] = $pthexp[0];
						$ctrl = $this->route[$key]['controller'];
						$controller_found = yes;
					}
					
				}
				if (strpos($kvalue, 'a:=') === 0) {
					if (count($pthexp) < 2) continue;
					if (explode(':=', $kvalue)[1] == $pthexp[1] && $action_found == NO) {
						$actn = $this->route[$key]['action'];
						$action_found = yes;
					}
					elseif (preg_match(REG_EXP_PICKUP_PATH, $kvalue, $matches_pua) === 1 && $action_found == NO) {
						$keys[$matches_pua[1]] = $pthexp[1];
						$actn = $this->route[$key]['action'];
						$action_found = yes;
					}
				}
				else {
					$actn = $this->route[$key]['action'];
				}
				if (strpos($kvalue, 'p:=') === 0) {
					if (count($pthexp) < 3) continue;
					if ((explode(':=', $kvalue)[1] == $pthexp[2] || strpos($pthexp[2], explode(':=', $kvalue)[1]) === 0) && $params_found == NO) {
						$prms = $this->route[$key]['params'];
						$params_found = yes;
					}
					elseif (preg_match(REG_EXP_PICKUP_PATH_II, $kvalue, $matches_pup) === 1 && $params_found == NO) {
						$matches_pupg = preg_grep('(^\d{1,2})', $matches_pup);
						$prmsexp = explode('/', $pthexp[2], count($matches_pupg));
						$pinc = 0;
						foreach ($matches_pupg as $pupg_value) {
							$keys[$pupg_value] = $prmsexp[$pinc];
							$pinc += 1;
						}
						$prms = $this->route[$key]['params'];
						$params_found = yes;
					}
				}
				else {
					$prms = $this->route[$key]['params'];
				}
				if (strpos($kvalue, 'q:=') === 0 && !empty($qryexp)) {
					$controller_found = no;
					$action_found = no;
					$params_found = no;
					$qres = substr($kvalue, strpos($kvalue, '['));
					$qres = trim($qres, '[]');
					$qresexp = explode('/', $qres);
					$qresexp = array_filter($qresexp);
					if ((is_array($qryexp) && !empty($qryexp)) && (is_array($qresexp) && !empty($qresexp)) && $controller_found == NO) {
						if (
							($qryexp[0] == '~' || $qresexp[0 == '*']) ||
							($qresexp[0] == $qryexp[0])
							) {
							array_shift($qresexp);
							array_shift($qryexp);
							$ctrl = $this->route[$key]['controller'];
							$controller_found = yes;
						}
						else {
							$controller_found = yes;
						}
						if (preg_match(REG_EXP_PICKUP_PATH, $qresexp[0], $matches_pua2) === 1 && $action_found == NO) {
							foreach (preg_grep('(^\d{1,2})', $matches_pua2) as $pua2_value) {
								if (array_key_exists(0, $qryexp) == YES) $keys[$pua2_value] = $qryexp[0];
								$actn = $this->route[$key]['action'];
								$action_found = yes;
								break;
							}
						}
						elseif ($qresexp[0] == $qryexp[0] && $action_found == NO) {
							$actn = $this->route[$key]['action'];
							$action_found = yes;
						}
						else {
							$action_found = yes;
						}
						if (preg_match(REG_EXP_PICKUP_PATH_II, $qresexp[1], $matches_pup2) === 1 && $params_found == NO) {
							$matches_pup2g = preg_grep('(^\d{1,2})', $matches_pup2);
							$pup2exp = [];
							if (array_key_exists(1, $qryexp) == YES) $pup2exp = explode('/', $qryexp[1], count($matches_pup2g));
							$minc = 0;
							foreach ($matches_pup2g as $pup2_value) {
								if (array_key_exists($minc, $pup2exp) === false) continue;
								$keys[$pup2_value] = $pup2exp[$minc];
								$minc += 1;
							}
							$prms = $this->route[$key]['params'];
							$params_found = yes;
						}
						else {
							$params_found = yes;
						}
					}
				}
			}
		}
		if (preg_match(REG_EXP_PICKUP_PAIR, $ctrl, $ctrl_matches) === 1) {
			$ctrl_g = preg_grep('(^\d{1,2})', $ctrl_matches);
			$ctrl_comp = "/";
			foreach ($ctrl_g as $cvalue) {
				if (array_key_exists($cvalue, $keys) === false) continue;
				$ctrl_comp .= $keys[$cvalue] . '/';
			}
			$this->controller = trim($ctrl_comp, '/');
		}
		else {
			$this->controller = $ctrl;
		}
		if (preg_match(REG_EXP_PICKUP_PAIR, $actn, $actn_matches) === 1) {
			$actn_g = preg_grep('(^\d{1,2})', $actn_matches);
			$actn_comp = '/';
			foreach ($actn_g as $avalue) {
				if (array_key_exists($avalue, $keys) === false) continue;
				$actn_comp .= $keys[$avalue] . '/';
			}
			$this->action = trim($actn_comp, '/');
		}
		else {
			$this->action = $actn;
		}
		if (preg_match(REG_EXP_PICKUP_PAIR_II, $prms, $prms_matches) === 1) {
			$prms_g = preg_grep('(^\d{1,2})', $prms_matches);
			$prms_comp = '/';
			foreach ($prms_g as $pvalue) {
				if (array_key_exists($pvalue, $keys) === false) continue;
				$prms_comp .= $keys[$pvalue] . '/';
			}
			$this->params = explode('/', trim($prms_comp, '/'));
		}
		else {
			$this->params = explode('/', $prms);
		}
		if ($controller_found || $action_found || $params_found) return YES;
		return NO;
	}
	/**
	 * Returns the `Controller` `string` for routing.
	 * @return string
	 */
	public function getController(): string
	{
		return $this->controller;
	}
	/**
	 * Returns the `Action` `string` for routing.
	 * @return string
	 */
	public function getAction(): string
	{
		return $this->action;
	}
	/**
	 * Returns an `iterable` of `Parameters` for routing.
	 * @return iterable|array
	 */
	public function getParams(): iterable
	{
		return $this->params;
	}
	/**
	 * Returns an iterable consisting of `Controller`, `Action` and `Parameters`,
	 * To be used in routing.
	 * @return iterable|array
	 */
	public function getAll(): iterable
	{
		return [
			$this->getController(),
			$this->getAction(),
			$this->getParams(),
			'controller' => $this->getController(),
			'action' => $this->getAction(),
			'params' => $this->getParams()
		];
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isGet()
	 */
	public function isGet(RequestInterface $request): bool
	{
		return YES;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isPost()
	 */
	public function  isPost(RequestInterface $request): bool
	{
		return YES;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isPatch()
	 */
	public function  isPatch(RequestInterface $request): bool
	{
		return YES;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isDelete()
	 */
	public function  isDelete(RequestInterface $request): bool
	{
		return YES;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isPut()
	 */
	public function  isPut(RequestInterface $request): bool
	{
		return YES;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::isJSON()
	 */
	public function isJSON(RequestInterface $request): bool
	{
		return YES;
	}
	/**
	 * 
	 * {@inheritDoc}
	 * @see Devoir\Interfaces\RequestInterface::is()
	 */
	public function is(?string $type, RequestInterface $request): bool
	{
		return YES;
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
	public function getData(string $data = null)
	{
		return null;
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
	 * @see Devoir\Interfaces\RequestInterface::isPut()
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
}

<?php
namespace Devoir;

/**
 * Router class. Used when creating an abstract routing to an existing controller without visiting it
 * @namespace Devoir
 * @author Muhammad Tahir Abdullahi <muhammedtahirabdullahi@gmail.com>
 * @copyright Copyright (c) Elftech Inc.
 * @package elfwap/devoir
 * @license https://opensource.org/licenses/mit-license.php MIT License
 *
 */
class Router extends Devoir
{
	/**
	 * 
	 * @var array $route holds loaded routes from `routes.php`
	 */
	private array $route;
	/**
	 * 
	 * @var string $controller holds controller name to be routed to.
	 */
	private string $controller;
	/**
	 * 
	 * @var string $action holds action name to be routed to.
	 */
	private string $action;
	/**
	 * 
	 * @var array $params holds parameters to be routed to.
	 */
	private array $params;
	/**
	 * 
	 * @var Devoir\BasicRequest $bsr
	*/
	private BasicRequest $bsr;
	/**
	 * 
	 * @param string|null $systemDir system directory where `routes.php` is contained in.
	 */
	public function __construct(?string $systemDir = null)
	{
		$this->bsr = new BasicRequest();
		$this->route = array();
		if(is_dir($systemDir)){
			$routes = $systemDir . DIRECTORY_SEPARATOR . 'routes.php';
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
		$controller_exists = no;
		$action_found = no;
		$action_exists = no;
		$params_found = no;
		$params_exists = no;
		$keys = array();
		$ctrl = "";
		$actn = "";
		$prms = "";
		foreach ($this->route as $key => $value) {
			$exp = explode(';', $key);
			if(is_array($exp)) $exp = array_filter($exp);
			$pth = $this->bsr->getPath();
			$qry = $this->bsr->getQuery('devoir');
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
					$controller_exists = YES;
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
					$action_exists = YES;
					if (count($pthexp) < 2) continue;
					if (explode(':=', $kvalue)[1] == $pthexp[1] && $action_found == NO) {
						$actn = $this->route[$key]['action'];
						$action_found = yes;
					}
					elseif (preg_match(REG_EXP_PICKUP_PATH, $kvalue, $matches_pua) === 1 && $action_found == NO) {
						if ($controller_exists && !$controller_found) continue;
						$keys[$matches_pua[1]] = $pthexp[1];
						$actn = $this->route[$key]['action'];
						$action_found = yes;
					}
				}
				if (strpos($kvalue, 'p:=') === 0) {
					$params_exists = YES;
					if (count($pthexp) < 3) continue;
					if ((explode(':=', $kvalue)[1] == $pthexp[2] || strpos($pthexp[2], explode(':=', $kvalue)[1]) === 0) && $params_found == NO) {
						$prms = $this->route[$key]['params'];
						$params_found = yes;
					}
					elseif (preg_match(REG_EXP_PICKUP_PATH_II, $kvalue, $matches_pup) === 1 && $params_found == NO) {
						if (($controller_exists && !$controller_found) || ($action_exists && !$action_found)) continue;
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
				if (strpos($kvalue, 'q:=') === 0 && !empty($qryexp)) {
					$controller_found2 = no;
					$action_found2 = no;
					$params_found2 = no;
					$qres = substr($kvalue, strpos($kvalue, '['));
					$qres = trim($qres, '[]');
					$qresexp = explode('/', $qres);
					$qresexp = array_filter($qresexp);
					if ((is_array($qryexp) && !empty($qryexp)) && (is_array($qresexp) && !empty($qresexp)) && $controller_found2 == NO) {
						if (
							($qryexp[0] == '~' || $qresexp[0 == '*']) ||
							($qresexp[0] == $qryexp[0])
							) {
							array_shift($qresexp);
							array_shift($qryexp);
							$ctrl = $this->route[$key]['controller'];
							$controller_found = yes;
							$controller_found2 = yes;
						}
						if (preg_match(REG_EXP_PICKUP_PATH, $qresexp[0], $matches_pua2) === 1 && $action_found2 == NO) {
							foreach (preg_grep('(^\d{1,2})', $matches_pua2) as $pua2_value) {
								if (array_key_exists(0, $qryexp) == YES) $keys[$pua2_value] = $qryexp[0];
								$actn = $this->route[$key]['action'];
								$action_found = yes;
								$action_found2 = yes;
								break;
							}
						}
						elseif ($qresexp[0] == $qryexp[0] && $action_found2 == NO) {
							$actn = $this->route[$key]['action'];
							$action_found = yes;
							$action_found2 = yes;
						}
						if (preg_match(REG_EXP_PICKUP_PATH_II, $qresexp[1], $matches_pup2) === 1 && $params_found2 == NO) {
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
							$params_found2 = yes;
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
		if (($controller_found && $controller_exists) && ($action_found && $action_exists) && ($params_found && $params_exists)) return YES;
		elseif (($action_found && $action_exists) && ($params_found && $params_exists) && !$controller_exists) return YES;
		elseif (($params_found && $params_exists) && !$action_exists && !$controller_exists) return YES;
		elseif (!$params_exists && ($action_found && $action_exists) && !$controller_exists) return YES;
		elseif (!$params_exists && !$action_exists && ($controller_found && $controller_exists)) return YES;
		else return NO;
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
}

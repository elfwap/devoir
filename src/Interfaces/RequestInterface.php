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
	public function isGet(RequestInterface $request);
	public function  isPost(RequestInterface $request);
	public function isPatch(RequestInterface $request);
	public function isDelete(RequestInterface $request);
	public function isPut(RequestInterface $request);
	public function isJSON(RequestInterface $request);
	public function is(?string $type, RequestInterface $request);
	public function setData($data);
	public function getData($data = null);
	
}
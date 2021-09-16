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
	public function isGet(RequestInterface $request): bool;
	public function  isPost(RequestInterface $request): bool;
	public function isPatch(RequestInterface $request): bool;
	public function isDelete(RequestInterface $request): bool;
	public function isPut(RequestInterface $request): bool;
	public function isJSON(RequestInterface $request): bool;
	public function is(?string $type, RequestInterface $request):bool;
	public function setData($data);
	public function getData($data = null);
	
}
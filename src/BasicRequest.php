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
class BasicRequest implements RequestInterface
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
}

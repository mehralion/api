<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 19.04.17
 * Time: 14:22
 */

namespace app\component\slim;


use app\component\slim\Middleware\Session\Helper as SessionHelper;
use app\component\WebUser;
use phpFastCache\Core\DriverAbstract;

/**
 * Class Slim
 * @package app\component\slim
 *
 * @property SessionHelper $session
 * @property WebUser $webUser
 * @property DriverAbstract $cache
 */
class Slim extends \Slim\Slim
{
	public function __construct(array $userSettings = array())
	{
		parent::__construct($userSettings);

		$this->container->singleton('session', function ($c) {
			//quick fix
			if (!session_id()) {
				session_start();
			}

			return new SessionHelper();
		});
	}

	/**
	 * @param string $name
	 * @return self
	 */
	public static function getInstance($name = 'default')
	{
		return parent::getInstance($name);
	}


	/**
	 * @param null $viewClass
	 * @return View
	 */
	public function view($viewClass = null)
	{
		return parent::view($viewClass);
	}
}
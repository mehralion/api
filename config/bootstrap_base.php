<?php
/**
 * Created by PhpStorm.
 * User: me
 * Date: 19.04.17
 * Time: 14:21
 */

define('ROOT_DIR', realpath(__DIR__ . '/../'));

$loader = require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$app = new app\component\slim\Slim(array(
	'mode'              => 'production',
	'templates.path'    => './template',
	'view'              => '\app\component\slim\View',
));

// Only invoked if mode is "production"
$app->configureMode('production', function () use ($app) {
	$app->config(require(ROOT_DIR . '/config/prod.php'));
});

$app->container->singleton('cache', function () use ($app) {
	$config = array(
		'memcache' => array(
			array('10.72.184.185', 11211, 1),
		),
	);
	\phpFastCache\CacheManager::setup($config);

	$Cache = \phpFastCache\CacheManager::Memcached();

	return $Cache;
});

$db_capital = $app->config('db.capital');
$capsule = new Capsule;

$capsule->addConnection([
	'driver'    => 'mysql',
	'host'      => $db_capital['host'],
	'database'  => $db_capital['dbname'],
	'username'  => $db_capital['username'],
	'password'  => $db_capital['password'],
	'charset'   => 'utf8',
	'collation' => 'utf8_unicode_ci',
	'prefix'    => '',
	'options' => array(
		\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET time_zone = \'+03:00\';',
		\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES CP1251;',
	)
]);

// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

$app->container->singleton('webUser', function () use ($app) {
	return new app\component\WebUser();
});
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
	/*$config = array(
		"storage"   =>  "files", // memcached, redis, ssdb, ..etc
		"path"      =>  ROOT_DIR."/tmp/cache/",
		'securityKey' => 'cache',
	);
	\phpFastCache\CacheManager::setup($config);
	return \phpFastCache\CacheManager::Files();*/

	$config = array(
		'memcache' => array(
			//array('10.72.184.185', 11211, 1),
			array('127.0.0.1', 11211, 1),
		),
	);
	\phpFastCache\CacheManager::setup($config);
	return \phpFastCache\CacheManager::Memcached();
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
		\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8;',
	)
]);

// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

$app->container->singleton('webUser', function () use ($app) {
	return new app\component\WebUser();
});

register_shutdown_function(function() {
	$error = error_get_last();
	if(isset($error)) {
		$message = sprintf('[%s] Level error: %s | message: %s | file: %s | line: %s', date('d.m.Y H:i:s'), $error['type'], $error['message'], $error['file'], $error['line']).PHP_EOL;

		$filename = 'other';
		$write = false;
		switch ($error['type']) {
			case E_ERROR:
			case E_PARSE:
			case E_COMPILE_ERROR:
			case E_CORE_ERROR:
				$filename = 'fatal';
				$write = true;
				break;
			case E_USER_ERROR:
			case E_RECOVERABLE_ERROR:
				$filename = 'error';
				$write = true;
				break;
			case E_WARNING:
			case E_CORE_WARNING:
			case E_COMPILE_WARNING:
			case E_USER_WARNING:
				$filename = 'warn';
				$write = false;
				break;
			case E_NOTICE:
			case E_USER_NOTICE:
				$filename = 'info';
				$write = false;
				break;
			case E_STRICT:
				$filename = 'debug';
				$write = true;
				break;
		}
		if($write === true) {
			\app\helper\FileHelper::write2($message, $filename);
		}
	}
});
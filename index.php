<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 18.11.2015
 */
require_once __DIR__ . '/config/bootstrap_web.php';


$app->map('/:action', function ($action) use ($app) {
	new \app\controller\ApiController($app, $action);
})->via('GET', 'POST')->name('api');

$app->run();

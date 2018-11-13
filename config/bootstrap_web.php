<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 19.10.2015
 */

require_once __DIR__ . '/bootstrap_base.php';

$app->add(new \app\component\slim\Middleware\Session\Session());
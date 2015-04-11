<?php



require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();

$app->mount('/api', new \App\AppControllerProvider());

$app['debug'] = true;
$app->run();
<?php

/**
 * Entry point for the Silexicon application.
 */

require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();

$app->mount('/', new \App\AppControllerProvider());
$app->mount('/words', new \App\WordControllerProvider());

$app['debug'] = true;
$app->run();
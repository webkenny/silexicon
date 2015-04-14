<?php

/**
 * Entry point for the Silexicon application.
 */

require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();

// @todo Fix this so we don't have database credentials hanging out in a PHP file blindly.
$app->register(new \Silex\Provider\DoctrineServiceProvider(), array(
   'db.options' => array(
        'driver'    => 'pdo_mysql',
        'host'      => 'localhost',
        'port'      => '8889',
        'dbname'    => 'lexicon',
        'user'      => 'root',
        'password'  => 'root',
        'charset'   => 'utf8'
   )
));

$app->mount('/', new \App\AppControllerProvider());
$app->mount('/words', new \App\WordControllerProvider());

$app['debug'] = true;
$app->run();
<?php

use Fun\CampaignControllerProvider;

require_once __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();

$app->mount('/api', new CampaignControllerProvider());

$app['debug'] = true;
$app->run();
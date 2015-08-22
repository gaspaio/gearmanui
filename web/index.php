<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\Debug\ExceptionHandler;

$app = new GearmanUI\GearmanUIApplication();

ExceptionHandler::register();

$app->run();

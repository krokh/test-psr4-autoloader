<?php

require __DIR__ . '/Psr4Autoloader.php';

use Vendor\Psr4Autoloader;

$autoloader = new Psr4Autoloader();
$autoloader->register();
$autoloader->addNamespace('App', dirname(dirname(__FILE__)) . '/src');

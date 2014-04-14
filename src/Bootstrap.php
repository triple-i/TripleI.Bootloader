<?php

$loader = require dirname(__DIR__) . '/vendor/autoload.php';
/** @var $loader \Composer\Autoload\ClassLoader */
$loader->addPsr4('TripleI\Bootloader\\', __DIR__);
$loader->add('TripleI\Bootloader\\', __DIR__);

define('DS', DIRECTORY_SEPARATOR);
define('ROOT_PATH', realpath(dirname(__FILE__).'/../'));
define('DATA', ROOT_PATH.DS.'data');
define('IS_EC2', (is_dir('/home/ec2-user') ? true: false));


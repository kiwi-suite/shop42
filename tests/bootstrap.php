<?php
namespace Shop42Test;

use Core42\Test\PHPUnit\Bootstrap;

chdir(__DIR__);
define('DEVELOPMENT_MODE', true);
date_default_timezone_set("UTC");

$loader = null;
if (file_exists('../vendor/autoload.php')) {
    $loader = include '../vendor/autoload.php';
    $loader->add('Shop42Test', __DIR__);
} elseif (file_exists('../../../vendor/autoload.php')) {
    $loader = include '../../../vendor/autoload.php';
    $loader->add('Shop42Test', __DIR__);
}

Bootstrap::init(['Core42']);

<?php

# Cache Constants
define('CACHE_ENABLED', 0);   // Enabling/Disabling Cache Tools
define('CACHE_DIR', __DIR__ . '/cache/');

# Athorization Constants
define('JWT_KEY', 'Iran-ApI%-Token!+Fgene*/');
define('JWT_ALG', 'HS256');


include 'App/iran.php';
include 'vendor/autoload.php';

spl_autoload_register(function ($class) {
    $class_file = str_replace('\\', '/', $class . ".php");
    $file_location = __DIR__ . "/$class_file";
    if (!(file_exists($file_location) AND is_readable($file_location))) {
        die("($file_location)  Not Found!!");
    }
    include $file_location;
});

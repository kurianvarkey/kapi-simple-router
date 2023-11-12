<?php

/**
 * This is the framework page which handles the application life cycle
 * 
 * @package  Kapi
 * @version  1.0.0
 * @author   K V P <kurianvarkey@yahoo.com>
 * @link     http://www.net4ideas.com
 */

namespace Kapi;

/**
 * Setting the required php ini values
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

/**
 * Definig the constants used in this application
 */
define('EXT', '.php');
define('CRLF', "\r\n");

/**
 * Setting the exception handler
 */
set_exception_handler(function ($e) {
    require_once path('framework') . 'error' . EXT;
    Error::exception($e);
});

/**
 * Setting the error handler
 */
set_error_handler(function ($code, $error, $file, $line) {
    require_once path('framework') . 'error' . EXT;
    Error::native($code, $error, $file, $line);
});

/**
 * Registering the shutdown function
 */
register_shutdown_function(function () {
    require_once path('framework') . 'error' . EXT;
    Error::shutdown();
});

/**
 * Report All Errors
 */
error_reporting(-1);

/**
 * Registering the autoloaders for the namespace to load
 */
spl_autoload_register(function ($class) {
    // replace namespace separators with directory separators in the relative 
    // class name, append with .php
    $class_path = strtolower(str_replace('\\', '/', $class));

    $file =  path("base") . $class_path . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    } else {
        die("$class not exists");
    }
});

/**
 * Loading the contstants and route definition files
 */
require path('framework') . 'constants.php';
require path('app') . 'routes.php';

exit(0);

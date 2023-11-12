<?php

declare(strict_types=1);

/**
 * Paythru - Test
 * 
 * This is the index page which handles all the operations
 *
 * @package  Kapi
 * @version  1.0.0
 * @author   K V P <kurianvarkey@yahoo.com>
 * @link     http://www.net4ideas.com
 */

define('KAPI_START', microtime(true));

//checking PHP version for this app. PHP 8.2 is needed
if (PHP_VERSION_ID < 80200) {
    $err = 'This application needs atleast PHP version 8.2 to run and you are running ' . PHP_VERSION . ', please upgrade PHP.' . PHP_EOL;
    if (!headers_sent()) {
        header('HTTP/1.1 500 Internal Server Error');
    }
    echo $err;
    trigger_error(
        $err,
        E_USER_ERROR
    );
}

require 'paths.php';

/**
 * Launch framework.
 */
require path('framework') . 'kapi.php';

<?php
/**
 * Important path definitions
 *
 * @package  Kapi
 * @version  1.0.0
 * @author   K V P <kurianvarkey@yahoo.com>
 * @link     http://www.net4ideas.com
 */

/**
 * Change to the current working directory.
 */
chdir(__DIR__);

/**
 * Define the directory separator for the environment.
 */
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

/**
 * Define the base directory for the environment.
 */
$GLOBALS['kapi_paths']['base'] = __DIR__ . DS;

/**
 * Define the app, framework, soucre paths
 */
$paths['app'] = 'app';
$paths['framework'] = 'kapi';
$paths['source'] = 'source';

/**
 * Define each constant if it hasn't been defined.
 */
foreach ($paths as $name => $path)
{
	if ( ! isset($GLOBALS['laravel_paths'][$name]))
	{
		$GLOBALS['kapi_paths'][$name] = realpath($path).DS;
	}
}

/**
 * Path helper function
 *
 * @param string $path
 * @return string
 */
function path(string $path): string
{
	return (isset($GLOBALS['kapi_paths'][$path]) ? $GLOBALS['kapi_paths'][$path] : '');
}

/**
 * Printing the output for debug
 *
 * @param mixed $data
 * @return void
 */
function pa(mixed $data) {
    echo "<pre>"; print_r($data); echo "</pre>";
}

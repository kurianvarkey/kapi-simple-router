<?php

/**
 * This is the error handlers for this application
 * 
 * @package  Kapi
 * @version  1.0.0
 * @author   K V P <kurianvarkey@yahoo.com>
 * @link     http://www.net4ideas.com
 */

namespace Kapi;

use ErrorException;

final class Error
{
    /**
     * Handle an exception and display the exception report.
     *
     * @param  Exception  $exception
     * @param  bool       $trace
     * @return void
     */
    public static function exception($exception, $trace = true)
    {
        ob_get_level() and ob_end_clean();

        $response = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ];

        http_response_code(500);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($response, JSON_PRETTY_PRINT);

        exit(1);
    }

    /**
     * Handle a native PHP error as an ErrorException.
     *
     * @param  int     $code
     * @param  string  $error
     * @param  string  $file
     * @param  int     $line
     * @return void
     */
    public static function native($code, $error, $file, $line)
    {
        if (error_reporting() === 0) return;

        // For a PHP error, we'll create an ErrorException and then feed that
        // exception to the exception method, which will create a simple view
        // of the exception details for the developer.
        $exception = new ErrorException($error, $code, 0, $file, $line);

        static::exception($exception);
    }

    /**
     * Handle the PHP shutdown event.
     *
     * @return void
     */
    public static function shutdown()
    {
        $error = error_get_last();

        if (!is_null($error)) {
            extract((array) $error, EXTR_SKIP);

            static::exception(new ErrorException($message, $type, 0, $file, $line), false);
        }
    }
}

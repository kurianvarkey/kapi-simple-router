<?php

/**
 * Routing definitions
 *
 * @package  Kapi
 * @version  1.0.0
 * @author   K V P <kurianvarkey@yahoo.com>
 * @link     http://www.net4ideas.com
 */

namespace App;

use App\Controllers\Books;
use App\Controllers\Home;
use App\Helpers\Response;
use Kapi\Router;

Router::get('/', function () {
    (new Home())->index();
});

Router::get('/books', function () {
    (new Books())->index();
});

Router::get('/books/stats', function () {
    (new Books())->stats();
});

Router::get('/books/download', function () {
    (new Books())->download();
});

Router::get('/books/$id', function ($id) {
    (new Books())->show(id: (string) $id);
});

Router::fallback(function () {
    echo Response::sendErrorResponse(
        errors: [
            "message" => "Endpoint not found"
        ]
    );
});

//finally we set all the route
Router::apply();

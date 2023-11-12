<?php

namespace App\Controllers;

use App\Helpers\Response;
use Kapi\Constants;

final class Home
{
    /**
     * Index
     *
     * @return void
     */
    public function index()
    {
        echo Response::sendResponse(
            data: [                
                "message" => "Welcome to the Books API"
            ],
            httpStatus: Constants::HTTP_OK
        );
    }
}

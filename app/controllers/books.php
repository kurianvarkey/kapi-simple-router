<?php

/**
 * Books Controller - this will handle service class and responses
 * 
 * @package  Kapi
 * @version  1.0.0
 * @author   K V P <kurianvarkey@yahoo.com>
 * @link     http://www.net4ideas.com
 */

namespace App\Controllers;

use App\Helpers\Response;
use App\Services\BookService;
use Exception;
use Kapi\Constants;

final class Books
{
    /**
     * @var BookService
     */
    private BookService $bookService;

    /**
     * Contructor
     */
    public function __construct()
    {
        $sourceFile = path('source') . 'books.xml';
        $this->bookService = new BookService($sourceFile);
    }

    /**
     * Index
     *
     * @return void
     */
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;
        $limit = $_GET['limit'] ?? 10;

        try {
            echo Response::sendResponse(
                data: $this->bookService->load()->list(search: $search, page: $page, limit: $limit)
            );
        } catch (Exception $e) {
            echo Response::sendErrorResponse(
                httpStatus: Constants::HTTP_INTERNAL_SERVER_ERROR,
                errors: [$e->getMessage()]
            );
        }
    }

    /**  
     * Show item by id
     *
     * @param string $id
     * @return void
     */
    public function show(string $id)
    {
        try {
            echo Response::sendResponse(
                data: $this->bookService->load()->findById(trim($id))
            );
        } catch (Exception $e) {
            echo Response::sendErrorResponse(
                httpStatus: Constants::HTTP_NOT_FOUND,
                errors: [$e->getMessage()]
            );
        }
    }

    /**
     * Get the stats
     *
     * @return void
     */
    public function stats()
    {
        try {
            echo Response::sendResponse(
                data: $this->bookService->load()->stats()
            );
        } catch (Exception $e) {
            echo Response::sendErrorResponse(
                httpStatus: Constants::HTTP_INTERNAL_SERVER_ERROR,
                errors: [$e->getMessage()]
            );
        }
    }

    /**
     * Download the file
     *
     * @return void
     */
    public function download()
    {
        return Response::download(
            filePath: $this->bookService->getSourceFile(),
            fileName: 'books.xml'
        );
    }
}

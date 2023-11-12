<?php

/**
 * Books collection class JSON output (implements the JsonSerializable interface)
 * This class will also handles the pagination
 * 
 * @package  Kapi
 * @version  1.0.0
 * @author   K V P <kurianvarkey@yahoo.com>
 * @link     http://www.net4ideas.com
 */

namespace App\Resources;

use JsonSerializable;

final class BookCollection implements JsonSerializable
{
    /**
     * Books array
     * @var array
     */
    private array $books;

    /**
     * Page value
     *
     * @var integer
     */
    public int $page;

    /**
     * Total records
     *
     * @var integer
     */
    public int $total;

    /**
     * Limit / records per page
     *
     * @var integer
     */
    public int $limit;

    /**
     * Total pages
     *
     * @var integer
     */
    public int $totalPages = 1;

    /**
     * Constructor
     *
     * @param array $books
     */
    public function __construct(array $books, int $page, int $limit = 10)
    {
        $this->page = $page;
        $this->total = count($books);
        $this->limit = $limit;

        if ($this->total > 0 && $this->limit > 0) {
            $this->totalPages = ceil($this->total / $this->limit);
        }

        $page = max($this->page, 1);
        $page = min($this->page, $this->totalPages);
        $offset = ($this->page - 1) * $this->limit;
        if ($offset < 0) {
            $offset = 0;
        }

        $this->books = array_slice($books, $offset, $this->limit);
    }

    /**
     * Serialize function
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        $result = [
            'total' => $this->total,
            'total_pages' => $this->totalPages,
            'per_page' => $this->limit,
            'current_page' => $this->page,
            'results' => []
        ];

        foreach ($this->books as $book) {
            $result['results'][] = [
                "id" => $book?->id,
                "author" => $book?->author,
                "title" => $book?->title,
                "price" => $book?->price,
            ];
        }

        return $result;
    }
}

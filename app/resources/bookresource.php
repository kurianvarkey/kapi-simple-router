<?php
/**
 * Book resource class for the JSON output
 * 
 * @package  Kapi
 * @version  1.0.0
 * @author   K V P <kurianvarkey@yahoo.com>
 * @link     http://www.net4ideas.com
 */

namespace App\Resources;

use JsonSerializable;

final class BookResource implements JsonSerializable
{
    /**
     * @var object
     */
    private object $book;

    /**
     * Constructor
     *
     * @param object $book
     */
    public function __construct(object $book)
    {
        $this->book = $book;
    }

    /**
     * Serialize function
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return [
            "id" => $this->book?->id,
            "author" => $this->book?->author,
            "title" => $this->book?->title,
            "genre" => $this->book?->genre,
            "price" => $this->book?->price,
            "publish_date" =>$this->book?->publish_date,
            "description" => $this->book?->description,
            "size" => $this->book?->size,
        ];
    }
}

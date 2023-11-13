<?php

/**
 * Books Service class which is responsible for all the data operations
 * 
 * @package  Kapi
 * @version  1.0.0
 * @author   K V P <kurianvarkey@yahoo.com>
 * @link     http://www.net4ideas.com
 */

namespace App\Services;

use App\Resources\BookCollection;
use App\Resources\BookResource;
use Exception;
use Kapi\Constants;
use SimpleXMLElement;
use XMLReader;

final class BookService
{
    /**
     * Source File
     * 
     * @var string
     */
    private string $sourceFile = '';

    /**
     * Records array
     * 
     * @var array
     */
    private array $records = [];

    /**
     * Total Book count
     *
     * @var integer
     */
    private int $totalBookCount = 0;

    /**
     * Total book price
     *
     * @var float
     */
    private float $totalBookPrice = 0;

    /**
     * Total book size
     *
     * @var int
     */
    private int $totalBookSize = 0;

    /**
     * Constructor
     */
    public function __construct(string $sourceFile = '')
    {
        $this->setSourceFile($sourceFile);
    }

    /**
     * Setting the source file
     *
     * @param string $sourceFile
     * @return BookService
     */
    public function setSourceFile(string $sourceFile): BookService
    {
        $this->sourceFile = $sourceFile;
        return $this;
    }

    /**
     * Get the list
     *
     * @param string $search
     * @param integer $page
     * @param integer $limit
     * @return BookCollection
     */
    public function list(string $search = '', int $page = 1, int $limit = 10): BookCollection
    {
        $results = [];
        if ($search != '') {
            $results = $this->getFilteredItems($search);
        } else {
            $results = $this->records;
        }

        if (count($results) > 0) {
            return new BookCollection($results, $page, $limit);
        }

        throw new Exception("No books found for given request", Constants::HTTP_NOT_FOUND);
    }

    /**
     * Get the book by id
     *
     * @param string $id
     * @return BookResource|exception
     */
    public function findById(string $id): ?BookResource
    {
        if (isset($this->records[$id])) {
            return new BookResource($this->records[$id]);
        }

        throw new Exception("Book not found for id $id", Constants::HTTP_NOT_FOUND);
    }

    /**
     * Get the stats
     *
     * @return array
     */
    public function stats(): array
    {
        return [
            'no_of_books' => $this->getBooksCount(),
            'average_book_price' => $this->getAverageBookPrice(),
            'average_book_size' => $this->getAverageBookSize(),
            'file_size' => $this->getBooksFileSize(),
        ];
    }

    /**
     * Load the xml data into an array
     *
     * @return BookService
     */
    public function load(): BookService
    {
        if ($this->getSourceFile() == "") {
            throw new Exception("Source file not set.", Constants::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (!$contents = file_get_contents($this->getSourceFile())) {
            throw new Exception($this->getSourceFile() . " file is not a valid xml or could not read.",  Constants::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $xml = new XMLReader();
            $xml->open($this->getSourceFile());

            // move to the first <book /> node
            while ($xml->read() && $xml->name !== 'book');

            // loop to read the whole xml
            while ($xml->name === 'book') {
                $book_xml = $xml->readOuterXml();
                if ($book = new SimpleXMLElement($book_xml)) {
                    $attributes = $book->attributes();
                    $id = trim((string) $attributes?->id) ?? '';
                    $price = (float) $book->price;
                    $size = mb_strlen($book_xml, '8bit');

                    $this->records[$id] = (object) [
                        "id" => $id,
                        "author" => (string) $book->author,
                        "title" => (string) $book->title,
                        "genre" => (string) $book->genre,
                        "price" => $price,
                        "publish_date" => (string) $book->publish_date,
                        "description" => (string) $book->description,
                        "size" => $size
                    ];

                    $this->totalBookPrice += $price;
                    $this->totalBookSize += $size;
                }

                // go to next <book />
                $xml->next('book');
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), Constants::HTTP_INTERNAL_SERVER_ERROR);
        }

        $this->totalBookCount = count($this->records);

        return $this;
    }

    /**
     * Getting the source file
     *
     * @return string
     */
    public function getSourceFile(): string
    {
        return $this->sourceFile;
    }

    /**
     * Get the books count
     *
     * @return integer
     */
    public function getBooksCount(): int
    {
        return $this->totalBookCount;
    }

    /**
     * Get Total book price
     *
     * @return float
     */
    public function getTotalBookPrice(): float
    {
        return $this->totalBookPrice;
    }

    /**
     *  Get Total book size
     *
     * @return int
     */
    public function getTotalBookSize(): int
    {
        return $this->totalBookSize;
    }

    /**
     * Get average book price
     *
     * @return float
     */
    public function getAverageBookPrice(): float
    {
        return round($this->getTotalBookPrice() / $this->getBooksCount(), 2);
    }

    /**
     * Get average book size
     *
     * @return int
     */
    public function getAverageBookSize(): int
    {
        return  round($this->getTotalBookSize() / $this->getBooksCount());
    }

    /**
     * Get the file size
     *
     * @return integer
     */
    public function getBooksFileSize(): int
    {
        return filesize($this->getSourceFile());
    }

    /**
     * Get the filtered results
     *
     * @param string $search
     * @return array
     */
    private function getFilteredItems(string $search): array
    {
        $results = [];
        array_walk($this->records, function ($book) use ($search, &$results) {
            if (stripos($book?->title, $search) !== false || stripos($book?->author, $search) !== false) {
                $results[] = $book;
            }
        });

        return $results;
    }
}

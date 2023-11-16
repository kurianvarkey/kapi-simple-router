<?php

namespace App\Adaptors;

use Exception;
use Kapi\Constants;
use SimpleXMLElement;
use XMLReader;

class XmlProvider implements iDataProvider 
{
    private string $source;

    public function __construct(string $source)
    {
        $this->setSource($source);
        $this->load();
    }

    public function setSource(string $source)
    {
        $this->source = $source;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function load()
    {
        if ($this->getSource() == "") {
            throw new Exception("Source file not set.", Constants::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            $xml = new XMLReader();
            $xml->open($this->getSource());

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
    }
}
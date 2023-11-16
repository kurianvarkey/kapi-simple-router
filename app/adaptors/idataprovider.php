<?php

namespace App\Adaptors;

interface iDataProvider{
    public function __construct(string $source);
    public function setSource(string $source);
    public function getSource();
    public function getTotalBookCount();
    public function getTotalBookPrice();
    public function gettotalBookSize();
}
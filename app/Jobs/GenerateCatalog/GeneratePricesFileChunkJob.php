<?php
namespace App\Jobs\GenerateCatalog;

class GeneratePricesFileChunkJob extends AbstractJob
{
    private $chunk;
    private $fileNum;

    public function __construct($chunk, $fileNum)
    {
        parent::__construct(); // Обов'язково викликаємо батьківський конструктор!
        $this->chunk = $chunk;
        $this->fileNum = $fileNum;
    }
}

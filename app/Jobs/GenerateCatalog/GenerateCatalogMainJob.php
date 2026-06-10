<?php

namespace App\Jobs\GenerateCatalog;

class GenerateCatalogMainJob extends AbstractJob
{
    public function handle()
    {
        $this->debug('start');

        // Спочатку кешуємо продукти (виконується миттєво, не в черзі)
        GenerateCatalogCacheJob::dispatchSync(); // У нових версіях Laravel dispatchNow замінено на dispatchSync

        // Створюємо ланцюг завдань формування файлів з цінами
        $chainPrices = $this->getChainPrices();

        // Основні підзавдання
        $chainMain = [
            new GenerateCategoriesJob,
            new GenerateDeliveriesJob,
            new GeneratePointsJob,
        ];

        // Підзавдання, які мають виконуватися останніми
        $chainLast = [
            new ArchiveUploadsJob,
            new SendPriceRequestJob,
        ];

        // Об'єднуємо всі масиви в один ланцюг
        $chain = array_merge($chainPrices, $chainMain, $chainLast);

        // Запускаємо генерацію товарів і "чіпляємо" до неї весь ланцюг
        GenerateGoodsFileJob::withChain($chain)->dispatch();

        $this->debug('finish');
    }

    private function getChainPrices()
    {
        $result = [];
        $products = collect([1, 2, 3, 4, 5]);
        $fileNum = 1;

        foreach ($products->chunk(1) as $chunk) {
            $result[] = new GeneratePricesFileChunkJob($chunk, $fileNum);
            $fileNum++;
        }

        return $result;
    }
}

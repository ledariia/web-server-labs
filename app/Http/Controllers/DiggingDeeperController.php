<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessVideoJob;
use App\Jobs\GenerateCatalog\GenerateCatalogMainJob;

class DiggingDeeperController extends Controller
{
    public function processVideo()
    {
        ProcessVideoJob::dispatch();
        return "Відео відправлено на обробку";
    }

    public function prepareCatalog()
    {
        GenerateCatalogMainJob::dispatch();
        return "Генерацію каталогу запущено!";
    }
}



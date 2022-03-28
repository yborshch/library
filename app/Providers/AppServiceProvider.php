<?php

namespace App\Providers;

use App\Services\Import\BookService;
use App\Services\Import\BookServiceInterface;
use App\Services\Import\FileType\FileTypeInterface;
use App\Services\Import\FileType\PDF;
use App\Services\Import\Parser\ParserInterface;
use App\Services\Import\Parser\Sites\Litres;
use App\Services\Import\Parser\Sites\Loveread;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            BookServiceInterface::class,
            BookService::class
        );
        $this->app->bind(
            ParserInterface::class,
            Loveread::class
        );
        $this->app->bind(
            ParserInterface::class,
            Litres::class
        );
        $this->app->bind(
            FileTypeInterface::class,
            PDF::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

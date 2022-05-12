<?php

namespace App\Providers;

use App\Repositories\Eloquent\AuthorRepository;
use App\Repositories\Eloquent\BookRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\ContextRepository;
use App\Repositories\Eloquent\FileRepository;
use App\Repositories\Eloquent\MessageRepository;
use App\Repositories\Eloquent\QueueRepository;
use App\Repositories\Eloquent\SeriesRepository;
use App\Repositories\Eloquent\TagRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\WatchAuthorRepository;
use App\Repositories\Eloquent\WatchBookRepository;
use App\Repositories\Eloquent\WatchSeriesRepository;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ContextRepositoryInterface;
use App\Repositories\Interfaces\FileRepositoryInterface;
use App\Repositories\Interfaces\MessageRepositoryInterface;
use App\Repositories\Interfaces\QueueRepositoryInterface;
use App\Repositories\Interfaces\SeriesRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\WatchAuthorRepositoryInterface;
use App\Repositories\Interfaces\WatchBookRepositoryInterface;
use App\Repositories\Interfaces\WatchSeriesRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AuthorRepositoryInterface::class, AuthorRepository::class);
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(SeriesRepositoryInterface::class, SeriesRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
        $this->app->bind(ContextRepositoryInterface::class, ContextRepository::class);
        $this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);
        $this->app->bind(QueueRepositoryInterface::class, QueueRepository::class);
        $this->app->bind(WatchAuthorRepositoryInterface::class, WatchAuthorRepository::class);
        $this->app->bind(WatchBookRepositoryInterface::class, WatchBookRepository::class);
        $this->app->bind(WatchSeriesRepositoryInterface::class, WatchSeriesRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

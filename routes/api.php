<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegistrationController;
use App\Http\Controllers\Api\Author\AuthorGetController;
use App\Http\Controllers\Api\Author\AuthorListController;
use App\Http\Controllers\Api\Author\AuthorRemoveController;
use App\Http\Controllers\Api\Author\AuthorSearchController;
use App\Http\Controllers\Api\Author\AuthorStoreController;
use App\Http\Controllers\Api\Author\AuthorUpdateController;
use App\Http\Controllers\Api\Author\BookListByAuthorController;
use App\Http\Controllers\Api\Book\BookDetailGetController;
use App\Http\Controllers\Api\Book\BookListController;
use App\Http\Controllers\Api\Book\BookRemoveController;
use App\Http\Controllers\Api\Book\BookStoreController;
use App\Http\Controllers\Api\Book\BookUpdateController;
use App\Http\Controllers\Api\Browser\BrowserIsExistController;
use App\Http\Controllers\Api\Category\CategoryGetController;
use App\Http\Controllers\Api\Category\CategoryListController;
use App\Http\Controllers\Api\Category\CategoryRemoveController;
use App\Http\Controllers\Api\Category\CategoryStoreController;
use App\Http\Controllers\Api\Category\CategoryUpdateController;
use App\Http\Controllers\Api\Import\ImportBookController;
use App\Http\Controllers\Api\Import\ImportGetController;
use App\Http\Controllers\Api\Message\AllMessagesClearController;
use App\Http\Controllers\Api\Message\MessageClearController;
use App\Http\Controllers\Api\Message\MessageGetController;
use App\Http\Controllers\Api\Queue\QueueGetController;
use App\Http\Controllers\Api\Queue\QueueRemoveBookController;
use App\Http\Controllers\Api\Series\SeriesGetController;
use App\Http\Controllers\Api\Series\SeriesListController;
use App\Http\Controllers\Api\Series\SeriesRemoveController;
use App\Http\Controllers\Api\Series\SeriesStoreController;
use App\Http\Controllers\Api\Series\SeriesUpdateController;
use App\Http\Controllers\Api\Tag\TagGetController;
use App\Http\Controllers\Api\Tag\TagListController;
use App\Http\Controllers\Api\Tag\TagRemoveController;
use App\Http\Controllers\Api\Tag\TagStoreController;
use App\Http\Controllers\Api\Tag\TagUpdateController;
use App\Http\Controllers\Api\Watch\WatchAuthorStoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth
Route::prefix('/auth')->group(function () {
    Route::post('/login',       [LoginController::class, '__invoke'])->name('auth.login');
    Route::post('/register',    [RegistrationController::class, '__invoke'])->name('auth.register');
});

//Route::middleware('auth:api')->group(function () {
Route::namespace('api')->group(function () {

    // Author
    Route::prefix('/author')->group(function () {
        Route::get('/list',     [AuthorListController::class, '__invoke'])->name('author.list');
        Route::post('/store',   [AuthorStoreController::class, '__invoke'])->name('author.store');
        Route::get('/get/{id}', [AuthorGetController::class, '__invoke'])->name('author.get');
        Route::post('/update',  [AuthorUpdateController::class, '__invoke'])->name('author.update');
        Route::post('/remove',  [AuthorRemoveController::class, '__invoke'])->name('author.remove');
        Route::get('/search',   [AuthorSearchController::class, '__invoke'])->name('author.search');
        Route::get('/books/{id}',  [BookListByAuthorController::class, '__invoke'])->name('author.books');
    });

    // Book
    Route::prefix('/book')->group(function () {
        Route::get('/list',     [BookListController::class, '__invoke'])->name('book.list');
        Route::post('/store',   [BookStoreController::class, '__invoke'])->name('book.store');
        Route::get('/{id}/detail', [BookDetailGetController::class, '__invoke'])->name('book.get');
        Route::post('/update',  [BookUpdateController::class, '__invoke'])->name('book.update');
        Route::post('/remove',  [BookRemoveController::class, '__invoke'])->name('book.remove');
    });

    // Category
    Route::prefix('/category')->group(function () {
        Route::get('/list',     [CategoryListController::class, '__invoke'])->name('category.list');
        Route::post('/store',   [CategoryStoreController::class, '__invoke'])->name('category.store');
        Route::get('/get/{id}', [CategoryGetController::class, '__invoke'])->name('category.get');
        Route::post('/update',  [CategoryUpdateController::class, '__invoke'])->name('category.update');
        Route::post('/remove',  [CategoryRemoveController::class, '__invoke'])->name('category.remove');
    });

    // Tag
    Route::prefix('/tag')->group(function () {
        Route::get('/list',     [TagListController::class, '__invoke'])->name('tag.list');
        Route::post('/store',   [TagStoreController::class, '__invoke'])->name('tag.store');
        Route::get('/get/{id}', [TagGetController::class, '__invoke'])->name('tag.get');
        Route::post('/update',  [TagUpdateController::class, '__invoke'])->name('tag.update');
        Route::post('/remove',  [TagRemoveController::class, '__invoke'])->name('tag.remove');
    });

    // Series
    Route::prefix('/series')->group(function () {
        Route::get('/list',     [SeriesListController::class, '__invoke'])->name('series.list');
        Route::post('/store',   [SeriesStoreController::class, '__invoke'])->name('series.store');
        Route::get('/get/{id}', [SeriesGetController::class, '__invoke'])->name('series.get');
        Route::post('/update',  [SeriesUpdateController::class, '__invoke'])->name('series.update');
        Route::post('/remove',  [SeriesRemoveController::class, '__invoke'])->name('series.remove');
    });

    // Import
    Route::get('/import/get',   [ImportGetController::class, '__invoke'])->name('import.get');
    Route::post('/import',      [ImportBookController::class, '__invoke'])->name('import.book');

    // Watch
    Route::prefix('/watch')->group(function () {
        Route::post('/store',   [WatchAuthorStoreController::class, '__invoke'])->name('watch.store');
    });

    // Message
    Route::prefix('/message')->group(function () {
        Route::get('/get',      [MessageGetController::class, '__invoke'])->name('message.get');
        Route::post('/{id}/clear', [MessageClearController::class, '__invoke'])->name('message.clear');
    });

    // Messages
    Route::prefix('/messages')->group(function () {
        Route::post('/clear',   [AllMessagesClearController::class, '__invoke'])->name('messages.clear');
    });

    // Browser extension
    Route::prefix('/browser')->group(function () {
        Route::get('/is-exist', [BrowserIsExistController::class, '__invoke'])->name('browser.is-exist');
    });

    // Queue
    Route::prefix('/queue')->group(function () {
        Route::get('/get',     [QueueGetController::class, '__invoke'])->name('queue.get');
        Route::post('/remove', [QueueRemoveBookController::class, '__invoke'])->name('queue.remove');
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

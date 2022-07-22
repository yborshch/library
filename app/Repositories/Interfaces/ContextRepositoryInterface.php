<?php
declare( strict_types = 1 );

namespace App\Repositories\Interfaces;

use App\ValueObject\Book;
use Symfony\Component\HttpFoundation\Request;

interface ContextRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param Book $book
     * @return bool
     */
    public function storeContext(Book $book): bool;

    /**
     * @param Request $request
     * @return bool
     */
    public function addBookmark(Request $request): bool;

    /**
     * @param Request $request
     * @return bool
     */
    public function removeBookmark(Request $request): bool;
}

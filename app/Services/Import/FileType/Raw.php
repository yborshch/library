<?php

namespace App\Services\Import\FileType;

use App\Repositories\Interfaces\ContextRepositoryInterface;
use App\ValueObject\Book;

class Raw implements FileTypeInterface
{
    /**
     * @var ContextRepositoryInterface
     */
    protected ContextRepositoryInterface $repository;

    /**
     * @param ContextRepositoryInterface $repository
     */
    public function __construct(ContextRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Book $book
     * @return bool
     */
    public function save(Book &$book): bool
    {
        $this->repository->storeContext($book);
        return true;
    }
}

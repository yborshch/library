<?php
declare( strict_types = 1 );

namespace App\Repositories\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface AuthorRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param int $id
     * @return mixed
     */
    public function listByAuthor(int $id);

    /**
     * @param Request $request
     * @return mixed
     */
    public function search(Request $request);
}

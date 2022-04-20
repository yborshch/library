<?php
declare( strict_types = 1 );

namespace App\Repositories\Interfaces;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param int $id
     * @return mixed
     */
    public function listByCategory(int $id): mixed;
}

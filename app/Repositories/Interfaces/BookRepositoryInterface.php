<?php
declare( strict_types = 1 );

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface BookRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param array $value
     * @return Model
     */
    public function store(array $value): Model;
}

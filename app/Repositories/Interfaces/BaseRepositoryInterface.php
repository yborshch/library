<?php
declare( strict_types = 1 );

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;

/**
 * Interface BaseRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface BaseRepositoryInterface
{
    /**
     * @param array $value
     * @return Model
     */
    public function store(array $value): Model;

    /**
     * @param Request $request
     * @return mixed
     */
    public function list(Request $request): mixed;

    /**
     * @param array $value
     * @return Model
     */
    public function update(array $value): Model;

    /**
     * @param int $id
     * @return Model
     */
    public function remove(int $id): Model;
}

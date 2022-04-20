<?php
declare( strict_types = 1 );

namespace App\Repositories\Interfaces;

use App\DTO\ListDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;

interface BookRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @param array $value
     * @return Model
     */
    public function store(array $value): Model;

    /**
     * @param Request $request
     * @return ListDTO
     */
    public function search(Request $request): ListDTO;
}

<?php

namespace App\Http\Resources;

use App\Repositories\Eloquent\WatchBookRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IsExistWatchBookRelationToBookResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $bookRepository = new WatchBookRepository();
        foreach ($this->resource['books'] as $item) {
            $item->isExistRelationToBook = $bookRepository->isExistRelationToBook($item);
        }

        return $this->resource;
    }
}

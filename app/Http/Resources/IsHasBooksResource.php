<?php

namespace App\Http\Resources;

use App\DTO\ListDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IsHasBooksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return ListDTO
     */
    public function toArray($request): ListDTO
    {
        foreach ($this->resource->list as $item) {
            $item->isHasBooks = $item->books()->exists();
        }

        return $this->resource;
    }
}

<?php

namespace App\Http\Resources;

use App\DTO\ListDTO;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return ListDTO
     */
    public function toArray($request): ListDTO
    {
        foreach ($this->resource->list as $book) {
            $book->queue = (bool)count($book->queue()->get());
            $book->new = Carbon::parse($book->created_at)->isToday();
        }

        return $this->resource;
    }
}

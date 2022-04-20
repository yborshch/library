<?php

namespace App\Http\Resources;

use App\Models\File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return Collection
     */
    public function toArray($request): Collection
    {
        $file = $this->resource->first()->image()->first();
        $this->resource->images = [
            'filename' => $file->filename,
            'extension' => $file->extension
        ];

        return $this->resource;
    }
}

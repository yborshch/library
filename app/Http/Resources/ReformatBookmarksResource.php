<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReformatBookmarksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return Collection
     */
    public function toArray($request): Collection
    {
        dd($this->resource);
        $result = [];
        foreach ($this->resource as $item) {
            foreach ($item->context as $contextItem) {
                if ($contextItem->bookmark !== null) {
                    $result[$contextItem->page] = $contextItem->bookmark;
                }
            }
        }
        $this->resource->bookmarks = $result;

        return $this->resource;
    }
}

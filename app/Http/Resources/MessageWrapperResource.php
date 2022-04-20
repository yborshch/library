<?php

namespace App\Http\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageWrapperResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $result = [
            'new' => 0,
            'messages' => $this->resource
        ];

        foreach ($this->resource as $item) {
            if (!$item->read) {
                $result['new']++;
            }
        }
        return $result;
    }
}

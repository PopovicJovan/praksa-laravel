<?php

namespace App\Http\Resources\Cast;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CastCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(
            function ($cast){
                return [
                    'id' => $cast->id,
                    'name' => $cast->name,
                    'image_path' => $cast->image_path
                ];
            }
        )->all();
    }
}

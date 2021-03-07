<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryTreeResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'total_product_count' => $this->total_product_count,
            'children_categories' => CategoryTreeResource::collection($this->whenLoaded('childrenCategories')),
        ];
    }
}

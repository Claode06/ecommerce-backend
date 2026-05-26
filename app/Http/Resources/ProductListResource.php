<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $variants = $this->variants->where('is_active', true);
        $minPrice = $variants->min('price');
        $maxPrice = $variants->max('price');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'thumbnail' => $this->thumbnailFile ? config('app.url').'/storage/'.$this->thumbnailFile->link : null,
            'gender' => (int) $this->gender,
            'category' => ['id' => $this->category?->id, 'name' => $this->category?->name],
            'brand' => ['id' => $this->brand?->id, 'name' => $this->brand?->name],
            'min_price' => (float) $minPrice,
            'max_price' => (float) $maxPrice,
            'has_active_variant' => $variants->isNotEmpty(),
            'created_at' => $this->created_at,
        ];
    }
}

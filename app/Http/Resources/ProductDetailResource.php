<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'features' => $this->features,
            'gender' => (int) $this->gender,
            'thumbnail' => $this->thumbnailFile ? config('app.url').'/storage/'.$this->thumbnailFile->link : null,
            'category' => new CategoryResource($this->category),
            'brand' => new BrandResource($this->brand),
            'images' => $this->images->map(fn ($img) => $img->fileStorage?->link ? config('app.url').'/storage/'.$img->fileStorage->link : null),
            'variants' => VariantResource::collection($this->variants->where('is_active', true)->values()),
            'reviews' => ReviewResource::collection($this->reviews),
            'avg_rating' => $this->reviews->avg('rating'),
            'total_reviews' => $this->reviews->count(),
            'created_at' => $this->created_at,
        ];
    }
}

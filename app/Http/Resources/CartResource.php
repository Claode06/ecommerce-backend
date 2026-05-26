<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_variant_id' => $this->product_variant_id,
            'quantity' => (int) $this->quantity,
            'variant' => new VariantResource($this->whenLoaded('productVariant')),
            'product' => $this->when($this->relationLoaded('productVariant') && $this->productVariant->relationLoaded('product'), function () {
                $p = $this->productVariant->product;
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'thumbnail' => $p->thumbnailFile ? '/storage/'.$p->thumbnailFile->link : null,
                ];
            }),
            'created_at' => $this->created_at,
        ];
    }
}

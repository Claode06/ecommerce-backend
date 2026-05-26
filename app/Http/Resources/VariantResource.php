<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $overridePrice = null;
        $activePromo = $this->promotionItems()
            ->whereHas('promotion', fn ($q) => $q->active())
            ->first();

        if ($activePromo) {
            $overridePrice = $activePromo->override_price;
        }

        return [
            'id' => $this->id,
            'label' => $this->label,
            'price' => (float) $this->price,
            'override_price' => $overridePrice ? (float) $overridePrice : null,
            'sku' => $this->sku,
            'stock' => (int) $this->warehouseStocks()->sum('quantity'),
            'is_active' => $this->is_active,
        ];
    }
}

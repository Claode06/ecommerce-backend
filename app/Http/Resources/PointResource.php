<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PointResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => (int) $this->type,
            'type_label' => ['Earn', 'Redeem', 'Adjustment'][$this->type - 1] ?? 'Unknown',
            'amount' => (int) $this->amount,
            'description' => $this->description,
            'order_number' => $this->order?->order_number,
            'created_at' => $this->created_at,
        ];
    }
}

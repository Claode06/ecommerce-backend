<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => (int) $this->status,
            'status_label' => ['Pending Payment', 'Paid', 'Processing', 'Shipped', 'Delivered', 'Cancelled'][$this->status - 1] ?? 'Unknown',
            'subtotal' => (float) $this->subtotal,
            'shipping_cost' => (float) $this->shipping_cost,
            'total' => (float) $this->total,
            'point_redeemed' => (int) $this->point_redeemed,
            'point_earned' => (int) $this->point_earned,
            'items' => OrderItemResource::collection($this->orderItems),
            'payment' => $this->payment ? new PaymentResource($this->payment) : null,
            'shipment' => $this->shipment ? new ShipmentResource($this->shipment) : null,
            'created_at' => $this->created_at,
        ];
    }
}

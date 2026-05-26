<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => (int) $this->status,
            'status_label' => ['Pending', 'Picked Up', 'In Transit', 'Out for Delivery', 'Delivered', 'Failed'][$this->status - 1] ?? 'Unknown',
            'courier_name' => $this->courier_name,
            'tracking_number' => $this->tracking_number,
            'shipping_cost' => (float) $this->shipping_cost,
            'delivered_at' => $this->delivered_at,
            'tracking_logs' => $this->trackingLogs->map(fn ($log) => [
                'status_label' => ['Pending', 'Picked Up', 'In Transit', 'Out for Delivery', 'Delivered', 'Failed'][$log->status - 1] ?? 'Unknown',
                'location' => $log->location,
                'note' => $log->note,
                'created_at' => $log->created_at,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}

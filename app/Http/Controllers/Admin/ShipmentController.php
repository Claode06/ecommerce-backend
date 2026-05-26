<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PointTransaction;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ShipmentController extends Controller
{
    public function index(): View
    {
        $shipments = Shipment::with(['order.user', 'warehouse'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.shipments.index', compact('shipments'));
    }

    public function create(Order $order)
    {
        $order->load('warehouse');

        return view('admin.shipments.create', compact('order'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'shipping_cost' => 'required|numeric|min:0',
            'courier_name' => 'nullable|string|max:100',
        ]);

        $existing = Shipment::where('order_id', $validated['order_id'])->first();

        if ($existing) {
            return back()->with('error', 'Pesanan ini sudah memiliki data pengiriman.');
        }

        DB::transaction(function () use ($validated) {
            $shipment = Shipment::create([
                'order_id' => $validated['order_id'],
                'warehouse_id' => $validated['warehouse_id'],
                'shipping_cost' => $validated['shipping_cost'],
                'courier_name' => $validated['courier_name'] ?? null,
                'status' => 1,
            ]);

            $shipment->trackingLogs()->create([
                'updated_by' => auth('admin')->id(),
                'status' => 1,
                'note' => 'Pengiriman dibuat.',
            ]);
        });

        return redirect()->route('admin.orders.show', $validated['order_id'])
            ->with('success', 'Data pengiriman berhasil dibuat.');
    }

    public function show(Shipment $shipment): View
    {
        $shipment->load(['order.user', 'trackingLogs.updatedBy', 'warehouse']);

        return view('admin.shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment): View
    {
        return view('admin.shipments.edit', compact('shipment'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'tracking_number' => 'nullable|string|max:100',
            'courier_name' => 'nullable|string|max:100',
        ]);

        $shipment->update($validated);

        return back()->with('success', 'Data pengiriman berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'status' => 'required|integer|in:2,3,4,5,6',
            'note' => 'nullable|string',
            'location' => 'nullable|string|max:100',
        ]);

        $newStatus = (int) $validated['status'];
        $validTransitions = [1 => [2], 2 => [3], 3 => [4], 4 => [5]];

        if (! isset($validTransitions[$shipment->status]) || ! in_array($newStatus, $validTransitions[$shipment->status])) {
            return back()->with('error', 'Perubahan status tidak valid.');
        }

        if ($newStatus === 2 && empty($shipment->tracking_number)) {
            return back()->with('error', 'Nomor resi wajib diisi sebelum status shipped.');
        }

        DB::transaction(function () use ($shipment, $newStatus, $validated) {
            $shipment->update(['status' => $newStatus]);

            $shipment->trackingLogs()->create([
                'updated_by' => auth('admin')->id(),
                'status' => $newStatus,
                'note' => $validated['note'] ?? null,
                'location' => $validated['location'] ?? null,
            ]);

            if ($newStatus === 5) {
                $shipment->update(['delivered_at' => now()]);
                $shipment->order->update(['status' => 5]);

                if ($shipment->order->point_earned > 0) {
                    $userPoint = $shipment->order->user->userPoint;
                    if ($userPoint) {
                        $userPoint->increment('balance', $shipment->order->point_earned);
                    }

                    PointTransaction::create([
                        'user_id' => $shipment->order->user_id,
                        'order_id' => $shipment->order->id,
                        'type' => 1,
                        'amount' => $shipment->order->point_earned,
                        'description' => 'Poin reward dari pesanan #'.$shipment->order->order_number,
                    ]);
                }
            }
        });

        return back()->with('success', 'Status pengiriman berhasil diperbarui.');
    }
}

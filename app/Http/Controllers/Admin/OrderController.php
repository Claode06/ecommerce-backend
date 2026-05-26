<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $orders = Order::with(['user', 'payment', 'shipment'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('search'), fn ($q) => $q->where('order_number', 'like', '%'.$request->input('search').'%'))
            ->when($request->filled('date_from'), fn ($q) => $q->whereDate('created_at', '>=', $request->input('date_from')))
            ->when($request->filled('date_to'), fn ($q) => $q->whereDate('created_at', '<=', $request->input('date_to')))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load([
            'user', 'warehouse', 'orderItems.productVariant.product',
            'payment.paymentAccount', 'payment.approvedBy', 'payment.rejectedBy',
            'shipment.trackingLogs',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        if (! in_array($order->status, [1, 3])) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan pada status ini.');
        }

        DB::transaction(function () use ($order) {
            $order->update(['status' => 6]);

            foreach ($order->orderItems as $item) {
                $stock = $order->warehouse->warehouseStocks()
                    ->where('product_variant_id', $item->product_variant_id)
                    ->lockForUpdate()
                    ->first();

                if ($stock) {
                    $stock->increment('quantity', $item->quantity);
                }
            }

            if ($order->point_redeemed > 0) {
                $point = $order->user->userPoint;
                if ($point) {
                    $point->increment('balance', $order->point_redeemed);
                }

                \App\Models\PointTransaction::create([
                    'user_id' => $order->user_id,
                    'order_id' => $order->id,
                    'type' => 3,
                    'amount' => $order->point_redeemed,
                    'description' => 'Pengembalian poin dari pembatalan pesanan #'.$order->order_number,
                ]);
            }
        });

        Log::info('Order cancelled', [
            'admin_id' => auth('admin')->id(),
            'order_id' => $order->id,
        ]);

        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dibatalkan.');
    }
}

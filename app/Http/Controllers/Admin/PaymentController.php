<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $payments = Payment::with(['order.user', 'paymentAccount'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function show(Payment $payment): View
    {
        $payment->load(['order.user', 'paymentAccount', 'proofFile']);

        return view('admin.payments.show', compact('payment'));
    }

    public function approve(Payment $payment)
    {
        if ($payment->status !== 1) {
            return back()->with('error', 'Pembayaran ini sudah diproses.');
        }

        DB::transaction(function () use ($payment) {
            $payment->update([
                'status' => 2,
                'approved_by' => auth('admin')->id(),
                'approved_at' => now(),
            ]);

            $payment->order->update([
                'status' => 3,
                'paid_at' => now(),
            ]);
        });

        Log::info('Payment approved', [
            'admin_id' => auth('admin')->id(),
            'payment_id' => $payment->id,
            'order_id' => $payment->order_id,
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function reject(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'rejected_reason' => 'required|string|min:10',
        ]);

        if ($payment->status !== 1) {
            return back()->with('error', 'Pembayaran ini sudah diproses.');
        }

        $payment->update([
            'status' => 3,
            'rejected_by' => auth('admin')->id(),
            'rejected_at' => now(),
            'rejected_reason' => $validated['rejected_reason'],
        ]);

        Log::info('Payment rejected', [
            'admin_id' => auth('admin')->id(),
            'payment_id' => $payment->id,
            'order_id' => $payment->order_id,
        ]);

        return back()->with('success', 'Pembayaran ditolak.');
    }
}

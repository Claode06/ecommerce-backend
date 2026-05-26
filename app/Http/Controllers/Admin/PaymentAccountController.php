<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PaymentAccountController extends Controller
{
    public function index(): View
    {
        $accounts = PaymentAccount::withCount('payments')->orderBy('bank_name')->paginate(15);

        return view('admin.payment-accounts.index', compact('accounts'));
    }

    public function create(): View
    {
        return view('admin.payment-accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:100',
            'account_name' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        PaymentAccount::create($validated);
        Log::info('Payment account created', ['admin_id' => auth('admin')->id()]);

        return redirect()->route('admin.payment-accounts.index')
            ->with('success', 'Akun pembayaran berhasil dibuat.');
    }

    public function edit(PaymentAccount $paymentAccount): View
    {
        return view('admin.payment-accounts.edit', compact('paymentAccount'));
    }

    public function update(Request $request, PaymentAccount $paymentAccount)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:100',
            'account_name' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        $paymentAccount->update($validated);
        Log::info('Payment account updated', ['admin_id' => auth('admin')->id(), 'id' => $paymentAccount->id]);

        return redirect()->route('admin.payment-accounts.index')
            ->with('success', 'Akun pembayaran berhasil diperbarui.');
    }

    public function destroy(PaymentAccount $paymentAccount)
    {
        $hasPayments = $paymentAccount->payments()->exists();

        if ($hasPayments) {
            return back()->with('error', 'Rekening masih digunakan dalam data pembayaran.');
        }

        $paymentAccount->delete();

        return redirect()->route('admin.payment-accounts.index')
            ->with('success', 'Akun pembayaran berhasil dihapus.');
    }
}

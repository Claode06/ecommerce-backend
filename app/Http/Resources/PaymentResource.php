<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payment_account' => [
                'bank_name' => $this->paymentAccount?->bank_name,
                'account_number' => $this->paymentAccount?->account_number,
                'account_name' => $this->paymentAccount?->account_name,
            ],
            'amount' => (float) $this->amount,
            'status' => (int) $this->status,
            'status_label' => ['Pending', 'Approved', 'Rejected', 'Expired'][$this->status - 1] ?? 'Unknown',
            'proof_url' => $this->proofFile ? config('app.url').'/storage/'.$this->proofFile->link : null,
            'rejected_reason' => $this->rejected_reason,
            'created_at' => $this->created_at,
        ];
    }
}

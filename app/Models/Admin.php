<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use SoftDeletes;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'status',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'integer',
            'password' => 'hashed',
            'last_login' => 'datetime',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function approvedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'approved_by');
    }

    public function rejectedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'rejected_by');
    }

    public function shipmentTrackingLogs(): HasMany
    {
        return $this->hasMany(ShipmentTrackingLog::class, 'updated_by');
    }
}

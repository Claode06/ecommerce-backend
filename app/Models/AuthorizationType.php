<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuthorizationType extends Model
{
    use SoftDeletes;

    protected $fillable = ['name'];

    public function authorizations(): HasMany
    {
        return $this->hasMany(Authorization::class);
    }
}

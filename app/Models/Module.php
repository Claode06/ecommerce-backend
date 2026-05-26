<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;

    protected $fillable = ['module_group_id', 'name', 'route', 'order', 'is_shown'];

    protected function casts(): array
    {
        return [
            'is_shown' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function moduleGroup(): BelongsTo
    {
        return $this->belongsTo(ModuleGroup::class);
    }

    public function authorizations(): HasMany
    {
        return $this->hasMany(Authorization::class);
    }
}

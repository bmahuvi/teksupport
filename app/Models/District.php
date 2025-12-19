<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{

    protected $guarded = ["id"];

    /**
     * Get the region that owns the District
     *
     * @return BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    protected $guarded=['id'];

    /**
     * Get all of the districts for the Region
     *
     * @return HasMany
     */
    public function districts(): HasMany
    {
        return $this->hasMany(District::class);
    }

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $guarded = ['id'];

    public static function get($key, $default = null)
    {
        return static::query()->where('key', $key)->value('value') ?? $default;
    }
}

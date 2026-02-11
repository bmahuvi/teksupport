<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Spatie\Activitylog\Models\Activity as ActivityModel;

class ActivityLog extends ActivityModel
{
    use HasUlids;
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketReply extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'is_opened' => 'boolean',
        'opened_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function markOpenedBy($userId): void
    {
        if ($userId == $this->user_id) {
            return;
        }

        if ($this->is_opened) {
            return;
        }

        $this->is_opened = true;
        $this->opened_by = $userId;
        $this->opened_at = now();
        $this->saveQuietly();
    }
}

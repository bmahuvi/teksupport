<?php

namespace App\Models;

use App\Enums\TicketPriority;
use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(TicketObserver::class)]
class Ticket extends Model
{
    protected $guarded = ['id'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function releases(): HasMany
    {
        return $this->hasMany(Release::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function ticketStatus(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
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

    public function markUnopened($userId): void
    {
        $this->is_opened = false;
        $this->opened_by = null;
        $this->opened_at = null;

        $this->saveQuietly();
    }

    public function isNotOpened(): bool
    {
        return !$this->is_opened;
    }

    protected function casts(): array
    {
        return [
            'priority' => TicketPriority::class,
            'is_opened' => 'boolean',
            'has_deadline' => 'boolean',
            'requires_approval' => 'boolean',
            'opened_at' => 'datetime',
            'custom_fields' => 'array',
        ];
    }
}

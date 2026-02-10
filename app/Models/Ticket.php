<?php

namespace App\Models;

use App\Enums\TicketPriority;
use App\Observers\TicketObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy(TicketObserver::class)]
class Ticket extends Model
{
    use HasUlids;

    protected $guarded = ['id'];


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

    public function activities(): HasMany
    {
        return $this->hasMany(TicketActivity::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class);
    }

    public function isNotClosed(): bool
    {
        return $this->status()->where('is_closing_status', false)->exists();
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'ticket_status_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function markOpenedBy($userId): void
    {
        if ($userId == $this->created_by) {
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
            'last_activity_at' => 'datetime',
        ];
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: function () {
                $form = $this->form()->with('fields')->first();

                if (!$form || !$this->custom_fields) {
                    return 'Ticket #' . $this->ticket_number;
                }

                $titleField = $form->fields->first(function ($field) {
                    return in_array(strtolower($field->name), ['title', 'subject', 'issue_title', 'ticket_title']);
                });

                if ($titleField && isset($this->custom_fields[$titleField->name])) {
                    return $this->custom_fields[$titleField->name];
                }

                $firstTextField = $form->fields->first(function ($field) {
                    return in_array($field->type, ['text', 'textarea']);
                });

                if ($firstTextField && isset($this->custom_fields[$firstTextField->name])) {
                    $value = $this->custom_fields[$firstTextField->name];
                    return is_string($value) ? substr(strip_tags($value), 0, 100) : 'Ticket #' . $this->ticket_number;
                }

                return 'Ticket #' . $this->ticket_number;
            }
        );
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    protected function content(): Attribute
    {
        return Attribute::make(
            get: function () {
                $form = $this->form()->with('fields')->first();

                if (!$form || !$this->custom_fields) {
                    return '';
                }

                $contentField = $form->fields->first(function ($field) {
                    return in_array(strtolower($field->name), ['content', 'description', 'details', 'message', 'issue_description']);
                });

                if ($contentField && isset($this->custom_fields[$contentField->name])) {
                    return $this->custom_fields[$contentField->name];
                }

                $firstTextareaField = $form->fields->first(function ($field) {
                    return $field->type === 'textarea';
                });

                if ($firstTextareaField && isset($this->custom_fields[$firstTextareaField->name])) {
                    return $this->custom_fields[$firstTextareaField->name];
                }

                return '';
            }
        );
    }
}

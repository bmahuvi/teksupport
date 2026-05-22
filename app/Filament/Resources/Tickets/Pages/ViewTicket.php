<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Enums\TicketPriority;
use App\Events\TicketReplyAdded;
use App\Filament\Resources\Tickets\TicketResource;
use App\Livewire\TicketAttachmentsDisplay;
use App\Livewire\TicketChatMessages;
use App\Livewire\TicketTimeline;
use App\Models\Form;
use App\Models\TicketStatus;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Facades\Filament;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form as BaseForm;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\TextSize;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    public ?array $replyData = [];

    public function infolist(Schema $schema): Schema
    {
        $canReply = Filament::auth()->user()->can('Reply:Ticket');

        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->id('ticket-view-tabs')
                    ->tabs([
                        Tab::make('Ticket View')
                            ->icon('heroicon-o-ticket')
                            ->schema([
                                Group::make()
                                    ->schema([
                                        Section::make()
                                            ->schema([
                                                Group::make()
                                                    ->schema([
                                                        TextEntry::make('created_by')
                                                            ->label('Created By')
                                                            ->formatStateUsing(fn($record) => $this->record->createdBy->name)
                                                            ->icon('heroicon-o-user-circle')
                                                            ->size(TextSize::Large)
                                                            ->weight(FontWeight::Bold),
                                                    ])
                                                    ->columnStart(1),

                                                Group::make()
                                                    ->schema([
                                                        TextEntry::make('Status')
                                                            ->formatStateUsing(fn($record) => $record->status?->name ?
                                                                "<span style='
                                                                        display: inline-flex;
                                                                        align-items: center;
                                                                        background-color: {$record->status->color}10;
                                                                        color: {$record->status->color};
                                                                        padding: 0.3rem 0.8rem;
                                                                        border-radius: 9999px;
                                                                        font-size: 0.7rem;
                                                                        font-weight: 600;
                                                                        line-height: 1;
                                                                        border: 1.5px solid {$record->status->color};
                                                                        white-space: nowrap;
                                                                    '>{$record->status->name}</span>"
                                                                : ''
                                                            )
                                                            ->html(),
                                                        TextEntry::make('priority')
                                                            ->badge(),
                                                    ])
                                                    ->columns(2)
                                                    ->columnStart(3),

                                            ])
                                            ->columns(2)
                                            ->heading(false),

                                        Section::make('Form Data')
                                            ->schema(fn() => $this->getCustomFieldsDisplay())
                                            ->columns()
                                            ->visible(fn() => count($this->getCustomFieldsDisplay()) > 0)
                                            ->collapsible(),

                                        Section::make()
                                            ->schema([
                                                Group::make()
                                                    ->schema([
                                                        Livewire::make(TicketChatMessages::class, ['ticket' => $this->record])
                                                            ->key(fn() => 'chat-' . $this->record->id),
                                                    ]),

                                                Group::make()
                                                    ->schema([
                                                        BaseForm::make([
                                                            RichEditor::make('content')
                                                                ->label('Add Reply')
                                                                ->required()
                                                                ->columnSpanFull()
                                                                ->placeholder('Write your reply here...')
                                                                ->toolbarButtons([
                                                                    ['bold', 'italic', 'underline', 'strike', 'subscript', 'superscript', 'link'],
                                                                    ['h2', 'h3', 'alignStart', 'alignCenter', 'alignEnd'],
                                                                    ['blockquote', 'codeBlock', 'bulletList', 'orderedList'],
                                                                    ['table', 'attachFiles'],
                                                                    ['undo', 'redo'],
                                                                ])
                                                                ->extraInputAttributes(['style' => 'min-height: 8rem;'])
                                                                ->fileAttachmentsDirectory('ticket-attachments/' . $this->record->id)
                                                                ->fileAttachmentsDisk('private')
                                                                ->fileAttachmentsVisibility('private')
                                                                ->fileAttachmentsAcceptedFileTypes(['image/png', 'image/jpeg']),
                                                        ])
                                                            ->statePath('replyData')
                                                            ->livewireSubmitHandler('submitReply')
                                                            ->footer([
                                                                Actions::make([
                                                                    Action::make('submitReply')
                                                                        ->submit('submitReply')
                                                                        ->label('Submit')
                                                                        ->color('success')
                                                                        ->visible(fn(): bool => $canReply)
                                                                ]),
                                                            ]),
                                                    ])
                                                    ->extraAttributes(['class' => 'border-t border-gray-700 pt-6 mt-0'])
                                            ])
                                            ->visible($canReply)
                                            ->heading('Conversation')
                                            ->icon('heroicon-o-chat-bubble-left-right')
                                            ->compact(false),
                                    ])
                                    ->columnSpan(['lg' => 2]),

                                Group::make()
                                    ->schema([
                                        Section::make('Ticket Details')
                                            ->icon('heroicon-o-document-text')
                                            ->schema([
                                                TextEntry::make('ticket_number')
                                                    ->label('Ticket Number')
                                                    ->copyable()
                                                    ->icon('heroicon-o-hashtag'),

                                                TextEntry::make('company.name')
                                                    ->label('Company')
                                                    ->icon('heroicon-o-building-office-2')
                                                    ->color('success'),

                                                TextEntry::make('created_at')
                                                    ->label('Created At')
                                                    ->dateTime('M d, Y H:i:s')
                                                    ->icon('heroicon-o-clock')
                                                    ->color('success'),

                                                TextEntry::make('updated_at')
                                                    ->label('Updated At')
                                                    ->dateTime('M d, Y H:i:s')
                                                    ->icon('heroicon-o-clock')
                                                    ->color('success'),

                                                TextEntry::make('assignee')
                                                    ->label('Assigned To')
                                                    ->formatStateUsing(fn($record) => $record->assigned_to ? $this->record->assignedTo->name : 'Unassigned')
                                                    ->default('Unassigned')
                                                    ->icon('heroicon-o-user-plus')
                                                    ->color('success'),

                                                TextEntry::make('last_activity_at')
                                                    ->label('Last Activity')
                                                    ->formatStateUsing(fn($record) => $this->getLastActivityDescription($record))
                                                    ->icon('heroicon-o-clock')
                                                    ->color('danger')
                                                    ->visible(fn($record) => $record->last_activity_at !== null),
                                            ])
                                            ->compact()
                                            ->collapsible(),

                                        Section::make('Recent Activities')
                                            ->icon('heroicon-o-clock')
                                            ->description('Last 5 activities')
                                            ->schema([
                                                Livewire::make(TicketTimeline::class, [
                                                    'ticket' => $this->record,
                                                    'limit' => 5
                                                ])->key(fn() => 'timeline-preview-' . $this->record->id),
                                            ])
                                            ->collapsed()
                                            ->collapsible(),
                                    ])
                                    ->columnSpan(['lg' => 1]),
                            ])
                            ->columns(3),

                        Tab::make('Timeline')
                            ->icon('heroicon-o-clock')
                            ->schema([
                                Section::make('Activity Timeline')
                                    ->icon('heroicon-o-clock')
                                    ->description('Complete activity history')
                                    ->schema([
                                        Livewire::make(TicketTimeline::class, ['ticket' => $this->record])
                                            ->key(fn() => 'timeline-full-' . $this->record->id),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull()
                    ->persistTab()
                    ->activeTab(1),
            ]);
    }

    protected function getCustomFieldsDisplay(): array
    {
        $form = null;

        if ($this->record->form_id) {
            $form = Form::with('fields')->find($this->record->form_id);
        }

        if (!$form) {
            $form = $this->record->form()->with('fields')->first();
        }

        if (!$form || !$form->fields->count() || empty($this->record->custom_fields)) {
            return [];
        }

        $entries = [];

        foreach ($form->fields as $field) {
            $value = $this->record->custom_fields[$field->name] ?? null;

            if ($value !== null) {
                if ($field->type === 'file' || $field->type === 'file_multiple') {
                    $entries[] = Livewire::make(
                        TicketAttachmentsDisplay::class,
                        [
                            'ticketId' => $this->record->id,
                            'files' => $value,
                            'label' => $field->label,
                        ]
                    )->key(fn() => 'files-' . $this->record->id);
                } else {
                    $formattedValue = $this->formatCustomFieldValue($value, $field);
                    $isHtml = in_array($field->type, ['textarea', 'rich_editor']);

                    if ($isHtml) {
                        $entries[] = TextEntry::make("custom_field_{$field->name}")
                            ->label($field->label)
                            ->state($formattedValue)
                            ->columnSpanFull()
                            ->html(true);
                    } else {
                        $entries[] = TextEntry::make("custom_field_{$field->name}")
                            ->label($field->label)
                            ->state($formattedValue);
                    }
                }
            }
        }

        return $entries;
    }

    protected function formatCustomFieldValue($value, $field): string
    {
        if ($this->isToggleOrCheckbox($field)) {
            return $value ? 'Yes' : 'No';
        }

        if ($field->type === 'select' || $field->type === 'radio') {
            $options = $field->options ?? [];
            return $options[$value] ?? $value;
        }

        if ($field->type === 'date' || $field->type === 'datetime') {
            try {
                $date = Carbon::parse($value);
                return $field->type === 'datetime'
                    ? $date->format('M d, Y H:i:s')
                    : $date->format('M d, Y');
            } catch (\Exception $e) {
                return $value;
            }
        }

        return is_string($value) ? $value : json_encode($value);
    }

    protected function isToggleOrCheckbox($field): bool
    {
        return $field->type === 'checkbox' || $field->type === 'toggle';
    }

    protected function getLastActivityDescription($record): string
    {
        $lastActivity = $record->activities()->latest()->first();

        if (!$lastActivity) {
            return 'No activity recorded';
        }

        $description = $lastActivity->description;
        $time = $lastActivity->created_at->format('M d, Y, H:i:s');

        return strtolower($description) . ' on ' . $time;
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);
        $this->record->markOpenedBy(Filament::auth()->user()?->getKey());

        $this->replyData = [
            'content' => '',
        ];
    }

    public function submitReply(): void
    {
        $user = Filament::auth()->user();
        $canReplyToTickets = $user->can('Reply:Ticket');
        if (!$canReplyToTickets) {
            Notification::make()->title('Unauthorized')
                ->danger()
                ->body('You do not have permission to reply to tickets.')
                ->send();

            return;
        }
        $data = $this->replyData ?? [];

        if (empty(($data['content'] ?? ''))) {
            Notification::make()
                ->title('Reply cannot be empty')
                ->danger()
                ->send();
            return;
        }

        $content = $data['content'];
        $htmlContent = is_array($content) ? $this->convertTiptapToHtml($content) : $content;
        $htmlContent = $this->moveTempFilesToPermanentStorage($htmlContent);

        $reply = $this->record->replies()->create([
            'content' => str($htmlContent)->sanitizeHtml(),
            'user_id' => Filament::auth()->user()?->getKey(),
        ]);

        $this->record->touch('last_activity_at');

        event(new TicketReplyAdded($this->record, $reply));

        $this->reset('replyData');

        $this->dispatch('$refresh');
        $this->dispatch('activity-added');
    }

    protected function convertTiptapToHtml(array $tiptapContent): string
    {
        if (!isset($tiptapContent['content']) || !is_array($tiptapContent['content'])) {
            return '';
        }

        $html = '';

        foreach ($tiptapContent['content'] as $block) {
            $html .= $this->processTiptapBlock($block);
        }

        return $html;
    }

    protected function processTiptapBlock(array $block): string
    {
        $type = $block['type'] ?? '';

        switch ($type) {
            case 'paragraph':
                $content = $this->processTiptapInlineContent($block['content'] ?? []);
                return "<p>{$content}</p>";

            case 'heading':
                $level = $block['attrs']['level'] ?? 2;
                $content = $this->processTiptapInlineContent($block['content'] ?? []);
                return "<h{$level}>{$content}</h{$level}>";

            case 'bulletList':
                $items = array_map(fn($item) => $this->processTiptapBlock($item), $block['content'] ?? []);
                return '<ul>' . implode('', $items) . '</ul>';

            case 'orderedList':
                $items = array_map(fn($item) => $this->processTiptapBlock($item), $block['content'] ?? []);
                return '<ol>' . implode('', $items) . '</ol>';

            case 'listItem':
                $content = array_map(fn($item) => $this->processTiptapBlock($item), $block['content'] ?? []);
                return '<li>' . implode('', $content) . '</li>';

            case 'image':
                $src = $block['attrs']['src'] ?? '';
                $alt = $block['attrs']['alt'] ?? '';
                $title = $block['attrs']['title'] ?? '';
                return "<img src=\"{$src}\" alt=\"{$alt}\" title=\"{$title}\">";

            case 'codeBlock':
                $content = $this->processTiptapInlineContent($block['content'] ?? []);
                return "<pre><code>{$content}</code></pre>";

            case 'blockquote':
                $content = array_map(fn($item) => $this->processTiptapBlock($item), $block['content'] ?? []);
                return '<blockquote>' . implode('', $content) . '</blockquote>';

            case 'table':
                $rows = array_map(fn($item) => $this->processTiptapBlock($item), $block['content'] ?? []);
                return '<table>' . implode('', $rows) . '</table>';

            case 'tableRow':
                $cells = array_map(fn($item) => $this->processTiptapBlock($item), $block['content'] ?? []);
                return '<tr>' . implode('', $cells) . '</tr>';

            case 'tableHeader':
                $content = array_map(fn($item) => $this->processTiptapBlock($item), $block['content'] ?? []);
                return '<th>' . implode('', $content) . '</th>';

            case 'tableCell':
                $content = array_map(fn($item) => $this->processTiptapBlock($item), $block['content'] ?? []);
                return '<td>' . implode('', $content) . '</td>';

            default:
                return '';
        }
    }

    protected function processTiptapInlineContent(array $content): string
    {
        $html = '';

        foreach ($content as $item) {
            $type = $item['type'] ?? '';

            if ($type === 'text') {
                $text = htmlspecialchars($item['text'] ?? '', ENT_QUOTES, 'UTF-8');
                $marks = $item['marks'] ?? [];

                foreach ($marks as $mark) {
                    switch ($mark['type']) {
                        case 'bold':
                            $text = "<strong>{$text}</strong>";
                            break;
                        case 'italic':
                            $text = "<em>{$text}</em>";
                            break;
                        case 'underline':
                            $text = "<u>{$text}</u>";
                            break;
                        case 'strike':
                            $text = "<s>{$text}</s>";
                            break;
                        case 'code':
                            $text = "<code>{$text}</code>";
                            break;
                        case 'link':
                            $href = htmlspecialchars($mark['attrs']['href'] ?? '#', ENT_QUOTES, 'UTF-8');
                            $target = isset($mark['attrs']['target']) ? ' target="' . $mark['attrs']['target'] . '"' : '';
                            $text = "<a href=\"{$href}\"{$target}>{$text}</a>";
                            break;
                        case 'subscript':
                            $text = "<sub>{$text}</sub>";
                            break;
                        case 'superscript':
                            $text = "<sup>{$text}</sup>";
                            break;
                    }
                }

                $html .= $text;
            } elseif ($type === 'image') {
                $src = htmlspecialchars($item['attrs']['src'] ?? '', ENT_QUOTES, 'UTF-8');
                $alt = htmlspecialchars($item['attrs']['alt'] ?? '', ENT_QUOTES, 'UTF-8');
                $html .= "<img src=\"{$src}\" alt=\"{$alt}\" style=\"display: inline-block; vertical-align: middle;\">";
            } elseif ($type === 'hardBreak') {
                $html .= '<br>';
            }
        }

        return $html;
    }

    protected function moveTempFilesToPermanentStorage(string $html): string
    {
        $pattern = '/<img[^>]+src=["\']([^"\']*livewire\/preview-file\/[^"\']+)["\']([^>]*)>/i';

        return preg_replace_callback($pattern, function ($matches) {
            $fullTag = $matches[0];
            $tempUrl = html_entity_decode($matches[1]);
            $otherAttributes = $matches[2];

            if (preg_match('/preview-file\/([^\?]+)/', $tempUrl, $fileMatches)) {
                $tempFileKey = urldecode($fileMatches[1]);

                try {
                    $tempFile = TemporaryUploadedFile::createFromLivewire($tempFileKey);

                    if ($tempFile) {
                        $originalFilename = $this->getOriginalFilename($tempFileKey);
                        $extension = pathinfo($originalFilename, PATHINFO_EXTENSION) ?: 'png';

                        $filename = uniqid() . '_' . time() . '.' . $extension;
                        $storagePath = "ticket-attachments/{$this->record->id}/{$filename}";

                        Storage::disk('private')->put(
                            $storagePath,
                            $tempFile->get()
                        );

                        $permanentUrl = url('/private/ticket-attachments/' . $this->record->id . '/' . $filename);

                        return '<img src="' . $permanentUrl . '"' . $otherAttributes . '>';
                    }
                } catch (\Exception $e) {
                }
            }

            return $fullTag;
        }, $html);
    }

    protected function getOriginalFilename(string $tempFileKey): string
    {
        if (preg_match('/-meta([^-]+)-/', $tempFileKey, $matches)) {
            $encoded = $matches[1];
            $decoded = base64_decode($encoded);
            return $decoded ?: 'file.png';
        }

        return 'file.png';
    }

    protected function getHeaderActions(): array
    {
        $statusActions = [];
        if ($this->canChangeStatus()) {
            foreach (TicketStatus::where('is_active', true)->get() as $status) {
                $statusActions[] = Action::make('status_' . $status->id)
                    ->label($status->name)
                    ->color(Color::hex($status->color))
                    ->visible(fn($record) => $record->ticket_status_id !== $status->id)
                    ->action(fn() => $this->changeStatus($status->id));
            }
        }

        $priorityActions = [];
        if ($this->canChangePriority()) {
            foreach (TicketPriority::cases() as $priority) {
                $priorityActions[] = Action::make('priority_' . $priority->value)
                    ->label($priority->getLabel())
                    ->color($priority->getColor())
                    ->visible(fn($record) => $record->priority !== $priority)
                    ->action(fn() => $this->changePriority($priority->value));
            }
        }

        return [
            ActionGroup::make($statusActions)
                ->label('Change Status')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->button()
                ->visible(fn() => $this->canChangeStatus()),

            ActionGroup::make($priorityActions)
                ->label('Change Priority')
                ->icon('heroicon-o-flag')
                ->color('gray')
                ->button()
                ->visible(fn() => $this->canChangePriority() && $this->record->isNotClosed()),

            Action::make('assignTicket')
                ->label('Assign Ticket')
                ->icon('heroicon-o-user-plus')
                ->color('gray')
                ->button()
                ->visible(fn() => $this->canAssignTicket() && $this->record->isNotClosed())
                ->schema([
                    Select::make('assignee')
                        ->label('Select Assignee')
                        ->required()
                        ->searchable()
                        ->getOptionLabelUsing(function ($value) {
                            $user = User::find($value);
                            return $user?->name;
                        })
                        ->options(function () {
                            return User::where('status', 1)
                                ->where('id', '!=', $this->record->assigned_to)
                                ->where(function ($q) {
                                    $q->where('company_id', Auth::user()->company_id)
                                        ->orWhere('company_id', $this->record->company_id);
                                })
                                ->orderBy('name')
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(fn($user) => [$user->getKey() => $user->name])
                                ->toArray();
                        })
                        ->default($this->record->assigned_to)
                        ->native(false),

                ])
                ->action(function (array $data) {
                    $this->assignTicket($data['assignee']);
                }),

            Action::make('delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->visible(fn() => Auth::user()->can('delete', $this->record))
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->delete();
                    return redirect()->to(TicketResource::getUrl('index'));
                }),
        ];
    }

    protected function canChangeStatus(): bool
    {
        return Auth::user()->can('ChangeStatus:Ticket', $this->record);
    }

    public function changeStatus($statusId): void
    {
        if (!$this->canChangeStatus()) {
            Notification::make()
                ->title('Unauthorized')
                ->danger()
                ->body('You do not have permission to change ticket status.')
                ->send();
            return;
        }
        $this->record->update(['ticket_status_id' => $statusId]);

        Notification::make()->title('Status updated.')->success()->send();

        $this->dispatch('$refresh');


    }

    protected function canChangePriority(): bool
    {
        return Auth::user()->can('ChangePriority:Ticket', $this->record);
    }

    public function changePriority($priority): void
    {
        if (!$this->canChangePriority()) {
            Notification::make()
                ->title('Unauthorized')
                ->danger()
                ->body('You do not have permission to change ticket priority.')
                ->send();
            return;
        }

        $oldPriority = $this->record->priority;
        $this->record->update(['priority' => $priority]);

        Notification::make()
            ->title('Priority updated')
            ->success()
            ->send();

        $this->dispatch('$refresh');
    }

    protected function canAssignTicket(): bool
    {
        return Auth::user()->can('assign', $this->record);
    }

    public function assignTicket($assigneeId): void
    {
        if (!$this->canAssignTicket()) {
            Notification::make()->title('Unauthorized')
                ->danger()
                ->body('You do not have permission to assign tickets.')
                ->send();
            return;
        }

        $oldAssigneeId = $this->record->assigned_to;
        $newAssigneeId = $assigneeId;

        $this->record->update(['assigned_to' => $newAssigneeId]);

        $this->record->markOpenedBy(Auth::user()->id);

        Notification::make()->title('Ticket assigned')
            ->success()
            ->send();

        $this->dispatch('$refresh');
    }
}

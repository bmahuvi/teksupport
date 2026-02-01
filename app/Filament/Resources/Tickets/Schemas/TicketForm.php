<?php

namespace App\Filament\Resources\Tickets\Schemas;

use App\Enums\TicketPriority;
use App\Models\Form;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()->schema([
                    Section::make('Ticket Details')
                        ->schema([
                            Select::make('form_id')
                                ->label('Ticket Form')
                                ->placeholder('Select form type')
                                ->options(function () {
                                    return Form::where('is_active', true)
                                        ->pluck('name', 'id')
                                        ->toArray();
                                })
                                ->required()
                                ->live()
                                ->afterStateUpdated(function (Set $set) {
                                    $set('custom_fields', []);
                                }),

                            Group::make()
                                ->schema(fn(Get $get, ?Model $record): array => static::getDynamicFormFields($record, $get('form_id')))
                                ->visible(fn(Get $get) => filled($get('form_id')))
                                ->columnSpanFull(),
                        ]),
                ])
                    ->columnSpan(2),

                Group::make()->schema([
                    Section::make('Ticket Properties')
                        ->schema([
                            Select::make('ticket_status_id')
                                ->label('Ticket Status')
                                ->relationship('ticketStatus', 'name')
                                ->default(TicketStatus::firstWhere('is_default_for_new', true)->id)
                                ->disabled(),

                            Select::make('priority')
                                ->options(TicketPriority::class)
                                ->enum(TicketPriority::class)
                                ->required()
                                ->default(TicketPriority::LOW),

                            Toggle::make('has_deadline')
                                ->default(false)
                                ->reactive()
                                ->afterStateUpdated(fn($state, callable $set) => $state ?: $set('deadline', null))
                                ->required(),

                            DatePicker::make('deadline')
                                ->visible(fn(callable $get) => $get('has_deadline'))
                                ->disabled(fn(callable $get) => !$get('has_deadline'))
                                ->required(fn(callable $get) => $get('has_deadline')),
                        ])
                ])
                    ->columnSpan(1),


            ])->columns(3);
    }

    protected static function getDynamicFormFields(?Model $record, $formId): array
    {
        $form = null;

        if ($formId) {
            $form = Form::with('fields')->find($formId);
        } elseif ($record instanceof Ticket) {
            if (isset($record->form_id)) {
                $form = Form::with('fields')->find($record->form_id);
            }

            if (!$form) {
                $form = Form::with('fields')->first();
            }
        }

        if (!$form || !$form->fields->count()) {
            if (!$formId) {
                return [
                    TextEntry::make('select_form')
                        ->label('')
                        ->state('Please select a form to proceed.')
                        ->columnSpanFull(),
                ];
            }

            return [
                TextEntry::make('no_form')
                    ->label('')
                    ->state('No form configured for this department')
                    ->columnSpanFull(),
            ];
        }

        $fields = [];
        $isDisabled = $record instanceof Ticket;

        foreach ($form->fields as $field) {
            $fieldComponent = null;
            $fieldName = "custom_fields.{$field->name}";

            switch ($field->type) {

                case 'text':
                    $fieldComponent = TextInput::make($fieldName)
                        ->label($field->label)
                        ->required($field->is_required)
                        ->disabled($isDisabled);
                    break;

                case 'textarea':
                    $fieldComponent = Textarea::make($fieldName)
                        ->label($field->label)
                        ->required($field->is_required)
                        ->rows(4)
                        ->disabled($isDisabled)
                        ->columnSpanFull();
                    break;

                case 'rich_editor':
                    $fieldComponent = RichEditor::make($fieldName)
                        ->label($field->label)
                        ->required($field->is_required)
                        ->disabled($isDisabled)
                        ->columnSpanFull();
                    break;

                case 'select':
                    $fieldComponent = Select::make($fieldName)
                        ->label($field->label)
                        ->options($field->options ?? [])
                        ->required($field->is_required)
                        ->disabled($isDisabled);
                    break;

                case 'select_multiple':
                    $fieldComponent = Select::make($fieldName)
                        ->label($field->label)
                        ->options($field->options ?? [])
                        ->multiple()
                        ->required($field->is_required)
                        ->disabled($isDisabled);
                    break;

                case 'radio':
                    $fieldComponent = Radio::make($fieldName)
                        ->label($field->label)
                        ->options($field->options ?? [])
                        ->required($field->is_required)
                        ->disabled($isDisabled);
                    break;

                case 'checkbox':
                    $fieldComponent = Checkbox::make($fieldName)
                        ->label($field->label)
                        ->required($field->is_required)
                        ->disabled($isDisabled);
                    break;

                case 'toggle':
                    $fieldComponent = Toggle::make($fieldName)
                        ->label($field->label)
                        ->required($field->is_required)
                        ->default(false)
                        ->disabled($isDisabled);
                    break;

                case 'file':
                case 'file_multiple':
                    $fieldComponent = FileUpload::make($fieldName)
                        ->label($field->label)
                        ->required($field->is_required)
                        ->disabled($isDisabled)
                        ->columnSpanFull()
                        ->downloadable()
                        ->openable()
                        ->disk('private')
                        ->visibility('private')
                        ->preserveFilenames()
                        ->directory(fn($record) => $record
                            ? "ticket-attachments/{$record->getKey()}"
                            : "ticket-attachments/temp"
                        );

                    if ($field->type === 'file_multiple') {
                        $fieldComponent->multiple();
                    }

                    $customRules = [];
                    if (!empty($field->validation_rules)) {
                        $rules = explode('|', $field->validation_rules);
                        foreach ($rules as $rule) {
                            $rule = trim($rule);
                            if (str_starts_with($rule, 'max_files:')) {
                                $fieldComponent->maxFiles((int)explode(':', $rule)[1]);
                            } elseif (str_starts_with($rule, 'min_files:')) {
                                $fieldComponent->minFiles((int)explode(':', $rule)[1]);
                            } else {
                                $customRules[] = $rule;
                            }
                        }
                    }

                    if (empty($customRules)) {
                        $customRules[] = 'max:5120';
                    }

                    $fieldComponent->rules($customRules);
                    break;
            }

            if ($fieldComponent) {
                $fields[] = $fieldComponent;
            }
        }

        return $fields;
    }
}

<?php

namespace App\Filament\Resources\Forms\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FieldsRelationManager extends RelationManager
{
    protected static string $relationship = 'fields';
    protected static ?string $recordTitleAttribute = 'label';
    protected static ?string $label = 'Form Field';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->helperText('Field name used internally (e.g., phone_number, company_name)')
                    ->required(),

                TextInput::make('label')
                    ->label('Label shown to users')
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->required(),

                Select::make('type')
                    ->required()
                    ->columnSpanFull()
                    ->options([
                        'text' => 'Text',
                        'textarea' => 'Textarea',
                        'email' => 'Email',
                        'tel' => 'Phone',
                        'number' => 'Number',
                        'url' => 'URL',
                        'select' => 'Dropdown',
                        'select_multiple' => 'Select Multiple',
                        'radio' => 'Radio Buttons',
                        'checkbox' => 'Checkbox',
                        'toggle' => 'Toggle',
                        'time' => 'Time',
                        'date' => 'Date',
                        'datetime' => 'Date & Time',
                        'file' => 'File Upload',
                        'file_multiple' => 'Multiple Files Upload',
                    ])
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if (!in_array($state, ['select', 'radio', 'select_multiple'])) {
                            $set('options', null);
                        }

                        if (in_array($state, ['file', 'file_multiple']) && empty($get('validation_rules'))) {
                            $set('validation_rules', 'mimes:jpg,jpeg,png,pdf,doc,docx|max:5120');
                        }
                    }),

                KeyValue::make('options')
                    ->label('Options')
                    ->keyLabel('Value')
                    ->valueLabel('Label')
                    ->visible(fn($get) => in_array($get('type'), ['select', 'radio', 'select_multiple']))
                    ->helperText('Add options for dropdown or radio fields')
                    ->columnSpanFull(),

                Toggle::make('is_required')
                    ->label('Required')
                    ->default(false)
                    ->columnSpanFull()
                    ->required(),

                Textarea::make('help_text')
                    ->label('Help Text')
                    ->rows(2)
                    ->maxLength(500)
                    ->columnSpanFull(),

                Textarea::make('validation_rules')
                    ->label('Additional Validation Rules')
                    ->placeholder('e.g. mimes:jpg,png|max:2048')
                    ->rows(3)
                    ->visible(fn($get) => !empty($get('type')))
                    ->columnSpanFull(),

                TextEntry::make('validation_examples')
                    ->label('Laravel validation rules (e.g., min:5|max:100)')
                    ->state(fn($get) => $this->getValidationExamples($get('type')))
                    ->visible(fn($get) => !empty($get('type')))
                    ->columnSpanFull(),

                TextInput::make('order')
                    ->label('Order')
                    ->numeric()
                    ->columnSpanFull()
                    ->default(function ($record) {
                        if ($record) {
                            return $record->order;
                        }

                        $maxOrder = $this->getOwnerRecord()->fields()->max('order');
                        return $maxOrder !== null ? $maxOrder + 1 : 0;
                    })
                    ->helperText('Display order (lower numbers appear first)'),
            ]);
    }

    protected function getValidationExamples(string $type): string
    {
        $examples = [
            'text' => 'min:3|max:255|regex:/^[a-zA-Z0-9\s]+$/',
            'textarea' => 'min:10|max:5000',
            'email' => 'email:rfc,dns',
            'tel' => 'regex:/^[0-9\+\-\(\)\s]+$/',
            'number' => 'integer|min:1|max:100',
            'url' => 'url|active_url',
            'file' => 'mimes:jpg,png,pdf|max:5120',
            'file_multiple' => 'mimes:jpg,png,pdf|max:5120|max_files:5',
            'date' => 'date|after:today',
            'datetime' => 'date|after:now',
            'time' => 'date_format:H:i|after:now',
        ];

        return $examples[$type] ?? 'max:255';
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('label')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('type')
                    ->color('info')
                    ->badge(),

                TextColumn::make('is_required')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state
                        ? 'Required'
                        : 'Optional'
                    )
                    ->color(fn($state) => $state ? 'success' : 'gray'),

                TextColumn::make('order')
                    ->sortable(),
            ])
            ->defaultSort('order')
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

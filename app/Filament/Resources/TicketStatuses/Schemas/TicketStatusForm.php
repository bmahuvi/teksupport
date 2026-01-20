<?php

namespace App\Filament\Resources\TicketStatuses\Schemas;

use App\Models\TicketStatus;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TicketStatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Status Name')
                    ->maxLength(255)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->maxLength(255)
                    ->unique(TicketStatus::class, 'slug', ignoreRecord: true)
                    ->required(),

                ColorPicker::make('color')
                    ->required()
                    ->default('#84cc16'),

                Toggle::make('is_default_for_new')
                    ->helperText('Is this color default for new ticket?')
                    ->default(false)
                    ->required(),

                Toggle::make('is_closing_status')
                    ->helperText('Is this color for closing a ticket?')
                    ->default(false)
                    ->required(),
            ]);
    }
}

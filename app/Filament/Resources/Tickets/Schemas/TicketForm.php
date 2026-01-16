<?php

namespace App\Filament\Resources\Tickets\Schemas;

use App\Models\Category;
use App\Models\Ticket;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->maxLength(255)
                    ->afterStateUpdated(fn(string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->maxLength(255)
                    ->unique(Ticket::class, 'slug', ignoreRecord: true),

                Select::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (!$state) {
                            $set('status', 'New');
                            return;
                        }

                        $requiresApproval = Category::whereKey($state)
                            ->value('requires_approval') ?? false;

                        if ($requiresApproval) {
                            $set('status', 'Waiting Approval');
                        } else {
                            $set('status', 'New');
                        }
                    }),

                TextInput::make('ticket_number')
                    ->required(),

                Select::make('priority')
                    ->options(['Low' => 'Low', 'Medium' => 'Medium', 'High' => 'High', 'Urgent' => 'Urgent'])
                    ->required(),

                Toggle::make('has_deadline')
                    ->default(false)
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set) => $state ?: $set('deadline', null))
                    ->required(),

                DatePicker::make('deadline')
                    ->disabled(fn(callable $get) => !$get('has_deadline'))
                    ->required(fn(callable $get) => $get('has_deadline')),
            ]);
    }
}

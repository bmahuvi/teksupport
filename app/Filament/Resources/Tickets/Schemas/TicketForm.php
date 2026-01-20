<?php

namespace App\Filament\Resources\Tickets\Schemas;

use App\Enums\TicketPriority;
use App\Models\Ticket;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ticket Details')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)
                            ->columnSpanFull()->schema([
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

                                Select::make('ticket_status_id')
                                    ->relationship('ticketStatus', 'name')
                                    ->label('Status'),

                                Select::make('category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
//                                        if (!$state) {
//                                            $set('status', 'New');
//                                            return;
//                                        }

//                                        $requiresApproval = Category::whereKey($state)
//                                            ->value('requires_approval') ?? false;
//
//                                        if ($requiresApproval) {
//                                            $set('status', 'Waiting Approval');
//                                        } else {
//                                            $set('status', 'New');
//                                        }
                                    }),

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
                                    ->disabled(fn(callable $get) => !$get('has_deadline'))
                                    ->required(fn(callable $get) => $get('has_deadline')),
                            ]),
                    ]),


            ]);
    }
}

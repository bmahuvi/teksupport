<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TicketInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug')
                    ->placeholder('-'),
                TextEntry::make('ticket_number'),
                TextEntry::make('createdBy.name'),

                TextEntry::make('category.name')
                    ->label('Category')
                    ->placeholder('-'),
                TextEntry::make('priority')
                    ->badge(),
                TextEntry::make('company.name')
                    ->label('Company')
                    ->placeholder('-'),
                TextEntry::make('ticketStatus.name')
                    ->label('Status')
                    ->formatStateUsing(fn($record) => $record->ticketStatus?->name ?
                        "<span style='
                                display: inline-flex;
                                align-items: center;
                                background-color: {$record->ticketStatus->color}10;
                                color: {$record->ticketStatus->color};
                                padding: 0.3rem 0.8rem;
                                border-radius: 9999px;
                                font-size: 0.7rem;
                                font-weight: 600;
                                line-height: 1;
                                border: 1.5px solid {$record->ticketStatus->color};
                                white-space: nowrap;
                            '>{$record->ticketStatus->name}</span>"
                        : ''
                    )
                    ->html(),
                IconEntry::make('requires_approval')
                    ->boolean(),
                IconEntry::make('has_deadline')
                    ->boolean(),
                TextEntry::make('deadline')
                    ->dateTime()
                    ->placeholder('-'),
                IconEntry::make('to_main')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Releases\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ReleaseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                IconEntry::make('uat_tested')
                    ->boolean(),
                IconEntry::make('prod_ready')
                    ->boolean(),
                TextEntry::make('test_results')
                    ->columnSpanFull(),
                TextEntry::make('start_date')
                    ->dateTime(),
                TextEntry::make('end_date')
                    ->dateTime(),
                IconEntry::make('downtime')
                    ->boolean(),
                TextEntry::make('downtime_from')
                    ->dateTime(),
                TextEntry::make('downtime_to')
                    ->dateTime(),
                IconEntry::make('rollback_available')
                    ->boolean(),
                TextEntry::make('remarks')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('ticket.title')
                    ->label('Ticket'),
                TextEntry::make('created_by')
                    ->numeric(),
                TextEntry::make('tested_by')
                    ->numeric(),
                TextEntry::make('prod_status')
                    ->badge(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('post_prod_issues')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

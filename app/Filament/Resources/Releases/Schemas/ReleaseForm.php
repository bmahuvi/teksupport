<?php

namespace App\Filament\Resources\Releases\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ReleaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('uat_tested')
                    ->required(),
                Toggle::make('prod_ready')
                    ->required(),
                Textarea::make('test_results')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('start_date')
                    ->required(),
                DateTimePicker::make('end_date')
                    ->required(),
                Toggle::make('downtime')
                    ->required(),
                DateTimePicker::make('downtime_from')
                    ->required(),
                DateTimePicker::make('downtime_to')
                    ->required(),
                Toggle::make('rollback_available')
                    ->required(),
                Textarea::make('remarks')
                    ->columnSpanFull(),
                Select::make('ticket_id')
                    ->relationship('ticket', 'title')
                    ->required(),
                TextInput::make('created_by')
                    ->required()
                    ->numeric(),
                TextInput::make('tested_by')
                    ->required()
                    ->numeric(),
                Select::make('prod_status')
                    ->options(['Success' => 'Success', 'Failed' => 'Failed', 'Not Tested' => 'Not tested'])
                    ->required(),
                Select::make('status')
                    ->options([
            'Pending' => 'Pending',
            'Postponed' => 'Postponed',
            'Rejected' => 'Rejected',
            'Completed' => 'Completed',
        ])
                    ->default('Pending')
                    ->required(),
                Textarea::make('post_prod_issues')
                    ->columnSpanFull(),
            ]);
    }
}

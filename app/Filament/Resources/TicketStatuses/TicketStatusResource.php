<?php

namespace App\Filament\Resources\TicketStatuses;

use App\Enums\NavigationGroups;
use App\Filament\Resources\TicketStatuses\Pages\CreateTicketStatus;
use App\Filament\Resources\TicketStatuses\Pages\EditTicketStatus;
use App\Filament\Resources\TicketStatuses\Pages\ListTicketStatuses;
use App\Filament\Resources\TicketStatuses\Schemas\TicketStatusForm;
use App\Filament\Resources\TicketStatuses\Tables\TicketStatusesTable;
use App\Models\TicketStatus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class TicketStatusResource extends Resource
{
    protected static ?string $model = TicketStatus::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $label = 'Ticket Status';

    protected static ?string $navigationLabel = 'Ticket Status';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroups::TICKETS->value;

    protected static ?int $navigationSort = 0;

    protected static ?string $pluralLabel = 'Ticket Statuses';

    public static function form(Schema $schema): Schema
    {
        return TicketStatusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TicketStatusesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTicketStatuses::route('/'),
            'create' => CreateTicketStatus::route('/create'),
            'edit' => EditTicketStatus::route('/{record}/edit'),
        ];
    }
}

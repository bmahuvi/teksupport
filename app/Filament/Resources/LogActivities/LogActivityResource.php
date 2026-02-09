<?php

namespace App\Filament\Resources\LogActivities;

use App\Enums\NavigationGroups;
use App\Filament\Resources\LogActivities\Pages\CreateLogActivity;
use App\Filament\Resources\LogActivities\Pages\EditLogActivity;
use App\Filament\Resources\LogActivities\Pages\ListLogActivities;
use App\Filament\Resources\LogActivities\Pages\ViewLogActivity;
use App\Filament\Resources\LogActivities\Schemas\LogActivityForm;
use App\Filament\Resources\LogActivities\Schemas\LogActivityInfolist;
use App\Filament\Resources\LogActivities\Tables\LogActivitiesTable;
use App\Models\LogActivity;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LogActivityResource extends Resource
{
    protected static ?string $model = \Spatie\Activitylog\Models\Activity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $recordTitleAttribute = 'log_name';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroups::SETTINGS->value;

    protected static ?int $navigationSort = 4;

    protected static ?string $label = 'Activity Log';

    protected static ?string $pluralLabel = 'Activity Logs';

    public static function canAccess(): bool
    {
        return Filament::auth()->user()->isSuperAdmin();
    }


    public static function infolist(Schema $schema): Schema
    {
        return LogActivityInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LogActivitiesTable::configure($table);
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
            'index' => ListLogActivities::route('/'),
            'view' => ViewLogActivity::route('/{record}'),
        ];
    }
}

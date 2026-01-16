<?php

namespace App\Filament\Resources\Releases;

use App\Filament\Resources\Releases\Pages\CreateRelease;
use App\Filament\Resources\Releases\Pages\EditRelease;
use App\Filament\Resources\Releases\Pages\ListReleases;
use App\Filament\Resources\Releases\Pages\ViewRelease;
use App\Filament\Resources\Releases\Schemas\ReleaseForm;
use App\Filament\Resources\Releases\Schemas\ReleaseInfolist;
use App\Filament\Resources\Releases\Tables\ReleasesTable;
use App\Models\Release;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReleaseResource extends Resource
{
    protected static ?string $model = Release::class;
    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    public static function form(Schema $schema): Schema
    {
        return ReleaseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ReleaseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReleasesTable::configure($table);
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
            'index' => ListReleases::route('/'),
            'create' => CreateRelease::route('/create'),
            'view' => ViewRelease::route('/{record}'),
            'edit' => EditRelease::route('/{record}/edit'),
        ];
    }
}

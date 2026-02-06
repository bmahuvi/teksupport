<?php

namespace App\Filament\Resources\Forms;

use App\Enums\NavigationGroups;
use App\Filament\Resources\Forms\Pages\CreateForm;
use App\Filament\Resources\Forms\Pages\EditForm;
use App\Filament\Resources\Forms\Pages\ListForms;
use App\Filament\Resources\Forms\Pages\ViewForm;
use App\Filament\Resources\Forms\RelationManagers\FieldsRelationManager;
use App\Filament\Resources\Forms\Schemas\FormForm;
use App\Filament\Resources\Forms\Schemas\FormInfolist;
use App\Filament\Resources\Forms\Tables\FormsTable;
use App\Models\Form;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class FormResource extends Resource
{
    protected static ?string $model = Form::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $label = 'Form';

    protected static string|UnitEnum|null $navigationGroup = NavigationGroups::SETTINGS->value;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return FormForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FormInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FormsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            FieldsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListForms::route('/'),
            'create' => CreateForm::route('/create'),
            'view' => ViewForm::route('/{record}'),
            'edit' => EditForm::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return self::getModel()::count();
    }
}

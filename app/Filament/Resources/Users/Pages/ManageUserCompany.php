<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Companies\CompanyResource;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Table;

class ManageUserCompany extends ManageRelatedRecords
{
    protected static string $resource = UserResource::class;

    protected static string $relationship = 'company';

    protected static ?string $relatedResource = CompanyResource::class;


    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}

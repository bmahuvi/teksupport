<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                Select::make('roles')->relationship('roles', 'name')->multiple(),

                Select::make('gender')
                    ->options(['Male' => 'Male', 'Female' => 'Female'])
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel(),


                Select::make('company_id')
                    ->relationship('company', 'name'),

            ]);
    }
}

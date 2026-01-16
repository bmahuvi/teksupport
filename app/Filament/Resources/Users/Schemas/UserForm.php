<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('gender')
                    ->options(['Male' => 'Male', 'Female' => 'Female'])
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('status')
                    ->options(['Active' => 'Active', 'Blocked' => 'Blocked', 'Inactive' => 'Inactive'])
                    ->required(),
                Toggle::make('change_password')
                    ->required(),
                DateTimePicker::make('last_password_change'),
                TextInput::make('login_attempts')
                    ->required()
                    ->numeric()
                    ->default(0),
                Select::make('company_id')
                    ->relationship('company', 'name'),
                TextInput::make('ulid')
                    ->required(),
            ]);
    }
}

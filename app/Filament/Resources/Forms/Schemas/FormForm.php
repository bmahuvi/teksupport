<?php

namespace App\Filament\Resources\Forms\Schemas;

use App\Models\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class FormForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->columnSpanFull()
                    ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(Form::class, 'slug', ignoreRecord: true),

                TextInput::make('initial')
                    ->required()
                    ->maxLength(3)
                    ->unique(Form::class, 'initial', ignoreRecord: true),

                Textarea::make('description')
                    ->rows(3)
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Toggle::make('requires_approval')
                    ->label('Is Approval Required')
                    ->required()
                    ->default(false),

                Toggle::make('is_active')
                    ->label('Is Active')
                    ->required()
                    ->default(true),
            ]);
    }
}

<?php

namespace App\Filament\Components;

use App\Models\District;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\Rule;

class DistrictComponent
{
    public static function make(): Select
    {
        return Select::make('district_id')
            ->label('District')
            ->noOptionsMessage('No districts found')
            ->options(function (callable $get) {
                $regionId = $get('region_id');

                if (!$regionId) {
                    return [];
                }
                return District::where('region_id', $regionId)
                    ->pluck('name', 'id');
            })
            ->createOptionForm([
                TextInput::make('name')
                    ->label('District Name')
                    ->required()
                    ->rule(Rule::unique('districts', 'name'))
                    ->validationMessages([
                        'unique' => 'This district already exists. Please select/activate it instead.',
                    ]),

                Hidden::make('region_id')
                    ->default(fn(callable $get) => $get('region_id'))
            ])
            ->createOptionUsing(function (array $data, callable $get) {
                $data['region_id'] = $get('region_id');

                return District::create([
                    'name' => $data['name'],
                    'region_id' => $get('region_id'),
                    'is_active' => true,
                ])->id;
            })
            ->getOptionLabelsUsing(function ($value) {
                return District::find($value)->name;
            })
            ->required()
            ->searchPrompt('Select districts by name')
            ->reactive()
            ->disabled(fn(callable $get) => !$get('region_id'));
    }
}

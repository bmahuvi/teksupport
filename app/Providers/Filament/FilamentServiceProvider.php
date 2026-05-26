<?php

namespace App\Providers\Filament;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        TextInput::configureUsing(function (TextInput $component): void {
            $component
                ->trim()
                ->autocomplete(false)
                ->autocapitalize('off');
        });

        Toggle::configureUsing(fn($component) => $component->inline(false)->default(true));

        Select::configureUsing(fn($component) => $component
            ->searchable()
            ->preload());

        EditAction::configureUsing(function (EditAction $component): void {
            $component
                ->icon('tabler-pencil');
        });

        Repeater::configureUsing(function (Repeater $repeater) {
            $repeater
                ->defaultItems(0);
        });

        DeleteAction::configureUsing(function (DeleteAction $component): void {
            $component
                ->icon('tabler-trash');
        });

        CreateAction::configureUsing(function (CreateAction $component): void {
            $component
                ->icon('tabler-plus');
        });

        Table::configureUsing(function (Table $table): void {
            $table->deferLoading()
                ->defaultCurrency('Tsh')
                ->paginationPageOptions([10, 25, 50, 100])
                ->defaultPaginationPageOption(25)
                ->filtersTriggerAction(
                    fn(Action $action) => $action
                        ->button()
                        ->label('Filter'),
                );
        });

        DatePicker::configureUsing(function (DatePicker $component): void {
            $component
                ->native(false);
        });

        Textarea::configureUsing(function (Textarea $component): void {
            $component
                ->disableGrammarly()
                ->trim()
                ->rows(2)
                ->autosize();
        });

        ActionGroup::configureUsing(function (ActionGroup $component): void {
            $component->icon(Heroicon::EllipsisHorizontal);
        });
    }
}

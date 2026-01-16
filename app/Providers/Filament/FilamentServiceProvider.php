<?php

namespace App\Providers\Filament;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
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

        Select::configureUsing(fn($component) => $component->searchable()->preload());

        EditAction::configureUsing(function (EditAction $component): void {
            $component
                ->icon('tabler-pencil');
        });

        DeleteAction::configureUsing(function (DeleteAction $component): void {
            $component
                ->icon('tabler-trash');
        });

        CreateAction::configureUsing(function (CreateAction $component): void {
            $component
                ->icon(Heroicon::Plus);
        });

        Textarea::configureUsing(function (Textarea $component): void {
            $component
                ->disableGrammarly()
                ->trim()
                ->autosize();
        });

        Table::configureUsing(function (Table $table): void {
            $table->deferLoading()
                ->defaultCurrency('Tsh')
                ->paginationPageOptions([10, 25, 50, 100])
                ->defaultPaginationPageOption(25);
        });
    }
}

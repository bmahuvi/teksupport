<?php

namespace App\Filament\Actions;

use App\Models\Company;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class DeactivateCompanyAction
{
    public static function make(): Action
    {
        return Action::make('deactivate-company')
            ->label('Deactivate Company')
            ->color('danger')
            ->icon('heroicon-s-lock-open')
            ->modalHeading('Deactivate Company')
            ->modalDescription('Are you sure you want to deactivate this company?')
            ->requiresConfirmation()
            ->visible(function ($record) {
                if (!$record instanceof Company) {
                    return false;
                }
                return Auth::user()->company->is_main && Auth::user()->can('DeactivateCompany:Company') && $record->is_active && !$record->is_main;
            })
            ->action(function ($record) {
                if (!$record instanceof Company) {
                    return;
                }
                $record->update(['is_active' => false]);

                Notification::make()
                    ->success()
                    ->title('Deactivate Company')
                    ->body('Your company has been deactivated successfully.');
            });
    }
}

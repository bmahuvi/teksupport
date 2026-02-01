<?php

namespace App\Filament\Actions;

use App\Models\Company;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ActivateCompanyAction
{
    public static function make(): Action
    {
        return Action::make('activate-company')
            ->label('Activate Company')
            ->color('success')
            ->icon('heroicon-s-check')
            ->modalHeading('Activate Company')
            ->modalDescription('Are you sure you want to activate this company?')
            ->requiresConfirmation()
            ->visible(function ($record) {
                if (!$record instanceof Company) {
                    return false;
                }
                return Auth::user()->company->is_main && Auth::user()->can('ActivateCompany:Company') && !$record->is_active && !$record->is_main;
            })
            ->action(function ($record) {
                if (!$record instanceof Company) {
                    return;
                }
                $record->update(['is_active' => true]);

                Notification::make()
                    ->success()
                    ->title('Activate Company')
                    ->body('Your company has been activated successfully.');
            });
    }
}

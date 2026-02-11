<?php

namespace App\Observers;

use App\Filament\Resources\Companies\CompanyResource;
use App\Models\Company;
use App\Notifications\CompanyActivatedNotification;
use App\Notifications\CompanyCreatedNotification;
use App\Notifications\CompanyDeactivatedNotification;

class CompanyObserver
{
    /**
     * Handle the Company "created" event.
     */
    public function created(Company $company): void
    {
        if ($company->email) {
            $url = CompanyResource::getUrl('view', ['record' => $company]);

            $company->notify(new CompanyCreatedNotification($company, $url));
        }
    }

    /**
     * Handle the Company "updated" event.
     */
    public function updated(Company $company): void
    {
        if ($company->is_active && $company->email) {
            $company->notify(new CompanyActivatedNotification($company));
        }

        if (!$company->is_active && $company->email) {
            $company->notify(new CompanyDeactivatedNotification($company));
        }
    }

    /**
     * Handle the Company "deleted" event.
     */
    public function deleted(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "restored" event.
     */
    public function restored(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "force deleted" event.
     */
    public function forceDeleted(Company $company): void
    {
        //
    }
}

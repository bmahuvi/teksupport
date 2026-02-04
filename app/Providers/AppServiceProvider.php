<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentTimezone;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }


        FilamentTimezone::set('Africa/Dar_es_Salaam');

        FilamentView::registerRenderHook(
            'panels::auth.login.form.after',
            fn(): Factory|View|\Illuminate\View\View => view('filament.pages.user-login')
        );
    }
}

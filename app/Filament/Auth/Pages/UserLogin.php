<?php

namespace App\Filament\Auth\Pages;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Support\Facades\Auth;

class UserLogin extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        $response = parent::authenticate();

        if (Auth::check()) {
            Auth::user()->logins()->create();
        }

        return $response;
    }
}

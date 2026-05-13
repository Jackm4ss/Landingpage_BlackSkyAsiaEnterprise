<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    public function getSubheading(): string
    {
        return 'Manage Black Sky events, articles, registrations, and portfolio work from one secure admin area.';
    }
}

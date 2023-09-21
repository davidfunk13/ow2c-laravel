<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Socialite::extend('battle_net', function ($app) {
        //     return Socialite::buildProvider(BattleNetProvider::class, config('services.battle_net'));
        // });
            }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

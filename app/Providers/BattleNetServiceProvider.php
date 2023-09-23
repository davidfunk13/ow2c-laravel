<?php

namespace App\Providers;

use App\Socialite\BattleNetProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class BattleNetServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerBattleNetDriver();
    }

    private function registerBattleNetDriver()
    {
        Socialite::extend('battle_net', function ($app) {
            $config = $app['config']['services.battle_net'];

            // Implement and return the custom Battle.net Socialite provider
            return new BattleNetProvider(
                $app['request'], $config['client_id'], $config['client_secret'], $config['redirect']
            );
        });
    }
}

<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Laravel\Socialite\Facades\Socialite;

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
        Schema::defaultStringLength(191);

        View::composer(['layouts.*'], function ($view) {
            try {
                if (Schema::hasTable('system_settings')) {
                    $view->with('systemSettings', \App\Models\SystemSetting::get());
                } else {
                    $view->with('systemSettings', null);
                }
            } catch (\Throwable $e) {
                $view->with('systemSettings', null);
            }
        });

        // Sur Windows, cURL n'a souvent pas de bundle CA : utiliser le magasin de certificats
        // natif du système (PHP 8.2+ / Curl 7.71+) pour les appels OAuth (Google, Facebook).
        if (defined('CURLSSLOPT_NATIVE_CA') && version_compare(curl_version()['version'] ?? '0', '7.71', '>=')) {
            $client = new Client([
                'curl' => [
                    \CURLOPT_SSL_OPTIONS => \CURLSSLOPT_NATIVE_CA,
                ],
            ]);
            foreach (['google', 'facebook'] as $driverName) {
                /** @var \Laravel\Socialite\Two\AbstractProvider $driver */
                $driver = Socialite::driver($driverName);
                $driver->setHttpClient($client);
            }
        }
    }
}

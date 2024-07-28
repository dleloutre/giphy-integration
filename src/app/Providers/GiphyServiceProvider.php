<?php

namespace App\Providers;

use App\Services\Giphy\GiphyAPI;
use Illuminate\Support\ServiceProvider;

class GiphyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(GiphyAPI::class, fn() => new GiphyAPI(
            config('services.giphy.uri'),
            config('services.giphy.apikey')
        ));
    }
}

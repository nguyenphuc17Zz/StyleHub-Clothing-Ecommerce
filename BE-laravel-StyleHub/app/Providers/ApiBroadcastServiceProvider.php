<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class ApiBroadcastServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        Broadcast::routes([
            'prefix' => 'api',
            'middleware' => ['auth:api'],
        ]);
        require base_path('routes/api-channels.php');
    }
}

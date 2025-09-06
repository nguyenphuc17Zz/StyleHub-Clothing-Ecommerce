<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
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


        // Dùng middleware web cho admin Blade
        Broadcast::routes(['middleware' => ['web']]);

        // Load các channel cho admin
        require base_path('routes/channels.php');
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use App\Models\WorkoutLog; // Add this line
use App\Observers\WorkoutLogObserver; // Add this line
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
        if (config('app.env') === 'production') { // <-- 2. Add this check
            URL::forceScheme('https'); // <-- 3. And this line
    }
}
}
<?php

namespace App\Providers;

use App\Services\TimePrediction;
use Illuminate\Support\ServiceProvider;

class TimePredictionProvider extends ServiceProvider
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
        //  // Automatically update the estimated time when the app starts
        $timePredictionService = new TimePrediction();
        $timePredictionService->predictAndUpdateEstimatedTime();
    }
}

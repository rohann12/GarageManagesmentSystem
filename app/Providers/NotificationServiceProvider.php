<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use App\Services\NotificationService;

class NotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $notificationService = new NotificationService();
        $notificationService->run(); // Check stock levels at boot

        // Share unread notifications with all views
        View::composer('*', function ($view) {
            $notifications = Notification::where('is_read', false)->get();
            $view->with('notifications', $notifications);
        });
    }

    public function register()
    {
        //
    }
}

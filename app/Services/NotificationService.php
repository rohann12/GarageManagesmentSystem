<?php
namespace App\Services;

use App\Models\Part;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationService
{
    public function run()
    {
        $this->checkStockLevels();
    }

    public function checkStockLevels()
    {
        $lowStockParts = Part::where('quantity', '<', 10)->get();

        foreach ($lowStockParts as $part) {
            // Check if a notification has been created in the last 3 hours for the same part
            $recentNotification = Notification::where('message', 'LIKE', "%{$part->part_name}%")
                ->where('created_at', '>=', Carbon::now()->subHours(3))
                ->first();

            if (!$recentNotification) {
                // Create a new notification if no recent notification exists
                Notification::create([
                    'user_id' => 1, // Notify admin or a specific user
                    'message' => "Low stock alert for part: {$part->part_name}. Only {$part->quantity} left.",
                ]);
            }
        }
    }

    public function notifyMechanic($mechanic, $order)
    {
        Notification::create([
            'user_id' => $mechanic->id, // Assuming $mechanic is the user model
            'message' => "You have been assigned to Order #{$order}.",
            'order_id' => $order,
            'is_read' => false,
        ]);
    }
}
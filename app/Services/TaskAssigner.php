<?php
namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\OrderRepair;

class TaskAssigner
{
    public function assignMechanic(array $repairIds)
    {
        // Fetch all online mechanics (users who aren't admin and are online)
        $mechanics = User::where('isAdmin', 0)
            ->where('is_online', 1) // Only include online mechanics
            ->get();

        // Initialize the best mechanic
        $bestMechanic = null;
        $bestScore = -1;

        foreach ($mechanics as $mechanic) {
            // Calculate the mechanic's eligibility score
            $score = $this->calculateMechanicScore($mechanic, $repairIds);

            // Choose the mechanic with the highest score
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMechanic = $mechanic;
            }
        }

        return $bestMechanic;
    }

    private function calculateMechanicScore(User $mechanic, array $repairIds)
    {
        $score = 0;

        // Factor 1: Experience points
        $score += $mechanic->experience_points;

        // Factor 2: Number of matching repairs the mechanic has previously completed
        $completedRepairs = OrderRepair::where('user_id', $mechanic->id)
            ->whereIn('repair_id', $repairIds)
            ->count();
        $score += $completedRepairs * 10; // Add weight to matching repair experience

        // Factor 3: Availability - assume a mechanic with fewer active tasks is more available
        $activeOrders = Order::where('assigned_to', $mechanic->id)
            ->where('status', 'in_progress') // Mechanic's active orders
            ->count();
        if ($activeOrders == 0) {
            $score += 50; // More weight for free mechanics
        } else {
            $score -= $activeOrders * 5; // Subtract points based on the number of active orders
        }

        return $score;
    }
}


<?php

namespace App\Services;

use App\Models\OrderRepair;
use App\Models\User;

class ExperiencePoint
{
    public function calculatePoints($taskDetails, $taskInfo, $userId)
    {
        // Base points based on complexity
        $basePoints = 0;
        switch ($taskDetails['repair_complexity'] ?? 'low') {
            case 'low':
                $basePoints += 5;
                break;
            case 'medium':
                $basePoints += 10;
                break;
            case 'high':
                $basePoints += 15;
                break;
            case 'very high':
                $basePoints += 20;
                break;
        }

        // Fetch current user XP
        $currentUser = User::find($userId);
        $currentUserXp = $currentUser->experience_points;

        // Fetch historical data for the user for this task
        $historicalData = OrderRepair::where('user_id', $userId)
            ->where('repair_id', $taskDetails['id'])
            ->get();

        // Fetch other users' completion times and XP for the same task
        $otherUsersData = OrderRepair::where('repair_id', $taskDetails['id'])
            ->where('user_id', '!=', $userId)
            ->get();

        // Calculate averages of other users' times and XP
        $otherUserAvgTime = $this->calculateAverageTime($otherUsersData);
        $otherUserAvgXp = $this->calculateAverageXp($otherUsersData);

        // Convert historical times of current user to seconds
        $historicalSeconds = $historicalData->map(function ($item) {
            return $this->timeToSeconds($item->time_taken);
        });

        // Calculate user's average time
        $userAverageTime = $historicalSeconds->avg() ?? 0;
        $userLastTime = $historicalSeconds->isNotEmpty() ? $historicalSeconds->last() : 0;

        // Ensure current completion time is in comparable format (seconds)
        $currentCompletionTime = $this->timeToSeconds($taskInfo['completion_time'] ?? '0:00:00');

        // Predict expected completion time using regression
        $predictedTime = $this->predictCompletionTime($historicalSeconds);

        // Adjust points based on performance vs predicted time
        if ($predictedTime > 0) {
            if ($currentCompletionTime < $predictedTime) {
                $basePoints += 3; // Bonus for being faster than predicted
            } elseif ($currentCompletionTime > $predictedTime) {
                $basePoints -= 3; // Penalty for being slower than predicted
            }
        }

        // Bonus or penalty based on current user performance vs their last time
        if ($userLastTime > 0 && $currentCompletionTime < $userLastTime) {
            $basePoints += 5; // Bonus for being faster than last time
        }

        // Adjust points based on user's average completion time
        if ($userAverageTime > 0) {
            if ($currentCompletionTime < $userAverageTime) {
                $basePoints += 3; // Bonus for being faster than average
            } elseif ($currentCompletionTime > $userAverageTime) {
                $basePoints -= 3; // Penalty for being slower than average
            }
        }

        // XP-based adjustment: compare user XP with the average XP of other users
        if ($otherUserAvgXp > 0) {
            if ($currentUserXp > $otherUserAvgXp) {
                // More XP, expected to perform better
                if ($currentCompletionTime < $otherUserAvgTime) {
                    $basePoints += 5; // Bonus for outperforming users with similar XP
                } else {
                    $basePoints -= 5; // Penalty for underperforming despite higher XP
                }
            } elseif ($currentUserXp < $otherUserAvgXp) {
                // Less XP, more leniency
                if ($currentCompletionTime < $otherUserAvgTime) {
                    $basePoints += 5; // Bonus for outperforming more experienced users
                }
            }
        }

        return max(0, $basePoints); // Ensure points are not negative
    }

    // Function to calculate average time of other users
    private function calculateAverageTime($data)
    {
        $times = $data->map(function ($item) {
            return $this->timeToSeconds($item->time_taken);
        });

        return $times->avg() ?? 0;
    }

    // Function to calculate average XP of other users
    private function calculateAverageXp($data)
    {
        $userIds = $data->pluck('user_id')->unique();
        $totalXp = User::whereIn('id', $userIds)->sum('experience_points');
        return count($userIds) > 0 ? $totalXp / count($userIds) : 0;
    }

    // Function to convert time string to seconds
    private function timeToSeconds($time)
    {
        if (!$time) return 0;
        $parts = explode(':', $time);
        return ($parts[0] * 3600) + ($parts[1] * 60) + ($parts[2]);
    }

    // Function to predict completion time using linear regression
    private function predictCompletionTime($historicalSeconds)
    {
        $n = count($historicalSeconds);
        if ($n === 0) return 0;

        // Calculate averages
        $meanX = $historicalSeconds->avg();
        $meanY = $historicalSeconds->avg(); // Using the same data for simplicity

        // Calculate slope (m) and intercept (b) for y = mx + b
        $numerator = 0;
        $denominator = 0;

        foreach ($historicalSeconds as $time) {
            $numerator += ($time - $meanX) * ($time - $meanY);
            $denominator += ($time - $meanX) ** 2;
        }

        // Slope
        $m = $denominator > 0 ? $numerator / $denominator : 0;
        // Intercept
        $b = $meanY - ($m * $meanX);

        // Use the last historical time as an input for prediction
        $predictedTime = ($m * $historicalSeconds->last()) + $b;

        return max(0, $predictedTime); // Ensure predicted time is not negative
    }
}

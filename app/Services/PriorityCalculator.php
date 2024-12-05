<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Repair;

class PriorityCalculator
{
    public function calculatePriority($startTime, $estimatedCompleted, $estimatedCost, $concatenatedJobs)
    {
        $timeEstimated = $this->calculateTimeEstimated($estimatedCompleted);
        $amount = (int) $estimatedCost;
        $numberOfJobs = $this->calculateNumberOfJobs($concatenatedJobs);
        $complexityScore = $this->calculateComplexityScore($concatenatedJobs); // Updated complexity handling

        $priority = 0;

        // Conditions based on timeEstimated (1 hour)
        if ($timeEstimated <= (60 * 60)) {
            $priority += 1;
        }
        // 30 min
        if ($timeEstimated <= (30 * 60)) {
            $priority += 1;
        } else if ($timeEstimated < (24 * 60 * 60)) { // Less than a day
            $priority += 3;

            // Urgency factor: Closer to 0 timeEstimated, higher priority
            $urgencyFactor = 1.0 - ($timeEstimated / (24 * 60 * 60));
            $priority += (int) ($urgencyFactor * 5); // Adjust the multiplier (5) as needed
        } else if ($timeEstimated < (2 * 24 * 60 * 60)) { // Less than 2 days
            $priority += 2;
        } else if ($timeEstimated < (3 * 24 * 60 * 60)) { // Less than 3 days
            $priority += 1;
        }

        // Conditions based on amount
        if ($amount >= 100) {
            $priority += 1;
        }
        if ($amount >= 500) {
            $priority += 1;
        }
        if ($amount >= 1000) {
            $priority += 1;
        }
        if ($amount >= 5000) {
            $priority += 1;
        }
        if ($amount >= 10000) {
            $priority += 1;
        }

        // Condition based on numberOfJobs
        if ($numberOfJobs > 0 && $numberOfJobs <= 5) {
            $priority += $numberOfJobs; // More jobs, more priority (up to 5)
        }

        // Adding the complexity score to the priority
        $priority += $complexityScore; // Sum of all job complexities

        return $priority;
    }

    private function calculateTimeEstimated($estimatedCompleted)
    {
        $currentTime = Carbon::now();
        $estimatedCompletedTime = Carbon::createFromFormat('Y-m-d\TH:i', $estimatedCompleted);
        return $estimatedCompletedTime->diffInMinutes($currentTime);
    }

    private function calculateNumberOfJobs($concatenatedJobs)
    {
        if (empty($concatenatedJobs)) {
            return 0;
        }
        return count(explode(', ', $concatenatedJobs)); // Assuming IDs are comma-separated
    }

    private function calculateComplexityScore($concatenatedJobs)
    {
        if (empty($concatenatedJobs)) {
            return 0;
        }

        // Split the concatenated job IDs using ". " as the delimiter
        $jobIds = explode('. ', $concatenatedJobs);

        // Fetch the job complexities from the repairs table using the Repair model
        $repairs = Repair::whereIn('id', $jobIds)->get(['repair_complexity']);

        if ($repairs->isEmpty()) {
            return 0;
        }

        // Map complexities to meaningful scores
        $complexityMapping = [
            'low' => 1,
            'medium' => 3,
            'high' => 6,
            'very high' => 10,
        ];

        // Sum the complexity scores
        $totalComplexityScore = 0;

        foreach ($repairs as $repair) {
            if (isset($complexityMapping[$repair->repair_complexity])) {
                $totalComplexityScore += $complexityMapping[$repair->repair_complexity];
            } else {
                // Handle cases where the complexity does not exist in the mapping (just in case)
                $totalComplexityScore += 0;
            }
        }
        // dd($totalComplexityScore);
        return $totalComplexityScore;
    }

}

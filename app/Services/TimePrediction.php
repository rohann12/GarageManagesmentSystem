<?php
namespace App\Services;

use App\Models\OrderRepair;
use App\Models\Repair;
use Illuminate\Support\Facades\Log;

class TimePrediction
{
    public function predictAndUpdateEstimatedTime()
    {
        // Step 1: Fetch time taken from order_repairs table
        $data = OrderRepair::select('repair_id', 'time_taken')
            ->whereNotNull('time_taken')
            ->get();
    
        if ($data->isEmpty()) {
            Log::info("No data to process.");
            return; // No data to process
        }
    
        // Prepare arrays for regression
        $Y = []; // Dependent variable (time taken)
        $repairIds = [];
    
        foreach ($data as $record) {
            $repairIds[] = $record->repair_id;
            $Y[] = $this->timeToSeconds($record->time_taken);
        }
    
        // Check if there's enough data for regression
        if (count($Y) < 2) {
            Log::info("Not enough data for regression.");
            return; // Not enough data for regression
        }
    
        // Step 2: Calculate the linear regression coefficients
        $n = count($Y);
        $sumY = array_sum($Y);
        $meanY = $sumY / $n;
    
        // Step 3: Update estimated_duration in repairs table based on the linear regression
        $repairs = Repair::whereIn('id', $repairIds)->get();
        foreach ($repairs as $repair) {
            $existingTimeSeconds = $this->timeToSeconds($repair->estimated_duration);
    
            // Using the mean of time_taken for predictions
            $predictedTimeSeconds = $meanY; 
    
            // Ensure non-negative time
            $predictedTimeSeconds = max(0, $predictedTimeSeconds);
            $predictedTime = gmdate('H:i:s', $predictedTimeSeconds);
    
            // Calculate deviation
            $deviation = abs($predictedTimeSeconds - $existingTimeSeconds) / $existingTimeSeconds;
    
            // Log details
            Log::info("Repair ID: {$repair->id}, Predicted Time: {$predictedTime}, Existing Time: {$repair->estimated_duration}, Deviation: " . ($deviation * 100) . "%");
    
            // Update only if the deviation is 15% or less
            if ($deviation <= 0.20) {
                $repair->update(['estimated_duration' => $predictedTime]);
            } else {
                Log::info("Skipping update for Repair ID: {$repair->id} due to excessive deviation.");
            }
        }
    }
    
    // Helper function to convert HH:MM:SS time format to seconds
    private function timeToSeconds($time)
    {
        sscanf($time, "%d:%d:%d", $hours, $minutes, $seconds);
        return isset($seconds)
            ? $hours * 3600 + $minutes * 60 + $seconds
            : $hours * 3600 + $minutes * 60;
    }
    
}

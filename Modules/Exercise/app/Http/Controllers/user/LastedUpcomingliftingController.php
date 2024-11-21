<?php

namespace Modules\Exercise\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Exercise\Models\Set;
use Illuminate\Support\Facades\Log;

class LastedUpcomingliftingController extends Controller
{
    public function getLastAndUpcoming(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'exercise_id' => 'required|integer',
        ]);

        try {
            // Fetch sets with comprehensive query
            $sets = Set::whereHas('userExercise', function ($query) use ($request) {
                $query->where('user_id', $request->user_id)
                    ->where('exercise_id', $request->exercise_id);
            })
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Handle no previous sets
            if ($sets->isEmpty()) {
                return response()->json([
                    'message' => 'No previous sets found',
                    'suggestion' => 'Start with a baseline assessment',
                    'status' => 'initial_training'
                ], 404);
            }

            // Analyze the latest set
            $latestSet = $sets->first();

            // Calculate comprehensive analytics with default values
            $analytics = $this->calculateSetAnalytics($sets);

            // Provide default upcoming set if analytics calculation fails
            $upcomingSet = $this->calculateUpcomingSet(
                $latestSet,
                $analytics
            );

            return response()->json([
                'last' => [
                    'weight' => $latestSet->weight,
                    'reps' => $latestSet->reps,
                ],
                'upcoming' => $upcomingSet,
                'analytics' => $analytics
            ]);
        } catch (\Exception $e) {
            Log::error('Lifting Progression Error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error calculating lifting progression',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
    /**
     * Calculate comprehensive set analytics
     */
    private function calculateSetAnalytics($sets)
    {
        // Calculate various performance metrics
        $volumes = $sets->map(function ($set) {
            return $set->weight * $set->reps;
        });

        $reps = $sets->pluck('reps');
        $weights = $sets->pluck('weight');

        return [
            'total_volume' => $volumes->sum(),
            'average_volume' => $volumes->avg(),
            'volume_consistency' => $this->calculateConsistency($volumes),
            'reps_range' => [
                'min' => $reps->min(),
                'max' => $reps->max(),
                'avg' => $reps->avg(),
            ],
            'weight_range' => [
                'min' => $weights->min(),
                'max' => $weights->max(),
                'avg' => $weights->avg(),
            ],
            'performance_trend' => $this->calculatePerformanceTrend($volumes),
            'fatigue_index' => $this->calculateFatigueIndex($sets)
        ];
    }

    /**
     * Calculate volume consistency
     */
    private function calculateConsistency($volumes)
    {
        if ($volumes->count() < 2) return 1;

        try {
            $changes = $volumes->sliding(2)->map(function ($pair) {
                // Ensure $pair is a collection and has at least 2 items
                if (!$pair instanceof \Illuminate\Support\Collection || $pair->count() < 2) {
                    return 0;
                }
                $first = $pair->first();
                $second = $pair->last();

                return $first ? abs(($second - $first) / $first) : 0;
            });

            return 1 - $changes->avg();
        } catch (\Exception $e) {
            Log::error('Consistency Calculation Error: ' . $e->getMessage());
            return 1; // Default to consistent
        }
    }

    private function calculatePerformanceTrend($volumes)
    {
        if ($volumes->count() < 3) return 'stable';

        try {
            $changes = $volumes->sliding(2)->map(function ($pair) {
                if (!$pair instanceof \Illuminate\Support\Collection || $pair->count() < 2) {
                    return 0;
                }
                $first = $pair->first();
                $second = $pair->last();

                return $first ? ($second - $first) / $first : 0;
            });

            $avgChange = $changes->avg();

            return match (true) {
                $avgChange > 0.1 => 'improving',
                $avgChange < -0.1 => 'declining',
                default => 'stable'
            };
        } catch (\Exception $e) {
            Log::error('Performance Trend Calculation Error: ' . $e->getMessage());
            return 'stable';
        }
    }

    /**
     * Calculate fatigue index
     */
    private function calculateFatigueIndex($sets)
    {
        if ($sets->count() < 3) return 0.5;

        // Compare first and last set performance
        $firstSet = $sets->last();
        $lastSet = $sets->first();

        $firstVolume = $firstSet->weight * $firstSet->reps;
        $lastVolume = $lastSet->weight * $lastSet->reps;

        $volumeDropoff = ($firstVolume - $lastVolume) / $firstVolume;

        return min(max($volumeDropoff, 0), 1);
    }



    private function calculateUpcomingSet($latestSet, $analytics)
    {
        // Extract the highest weight ever lifted
        $highestWeight = $analytics['weight_range']['max'];

        // Calculate weight progression based on the highest weight
        $weightProgression = $this->calculateWeightProgression($highestWeight, $latestSet);

        // Intelligent rep calculation
        $repProgression = $this->calculateRepProgression($latestSet, $highestWeight, $analytics);

        // Log detailed information for debugging
        Log::info('Upcoming Set Calculation', [
            'highest_previous_weight' => $highestWeight,
            'latest_set_weight' => $latestSet->weight,
            'latest_set_reps' => $latestSet->reps,
            'weight_progression' => $weightProgression,
            'rep_progression' => $repProgression
        ]);

        return [
            'weight' => round($weightProgression, 1),
            'reps' => $repProgression
        ];
    }


    private function calculateWeightProgression($highestWeight, $latestSet)
    {
        $baseIncrement = 2.5; // Standard plate increment

        // Progression logic based on the highest weight lifted
        return $highestWeight + $baseIncrement;
    }


    private function calculateRepProgression($latestSet, $highestWeight, $analytics)
    {
        // Comprehensive rep calculation strategy
        $performanceTrend = $analytics['performance_trend'];
        $repRange = $analytics['reps_range'];
        $fatigue = $analytics['fatigue_index'];

        // Base calculations
        $baseReps = $latestSet->reps;
        $weightDifference = $latestSet->weight - $highestWeight;

        // Dynamic rep adjustment logic
        $repAdjustment = match (true) {
            // Significant weight increase (close to or above previous max)
            $latestSet->weight >= $highestWeight * 0.9 =>
            // Reduce reps when approaching max weight
            max(3, $baseReps - ceil(($latestSet->weight - $highestWeight) / 20)),

            // Improving performance with low fatigue
            $performanceTrend === 'improving' && $fatigue < 0.4 =>
            $baseReps + 1,

            // Stable performance
            $performanceTrend === 'stable' =>
            $baseReps,

            // Declining performance or high fatigue
            $performanceTrend === 'declining' || $fatigue > 0.7 =>
            max(3, $baseReps - 1),

                // Default conservative approach
            default => $baseReps
        };

        // Boundary conditions
        return max(
            3,  // Minimum 3 reps
            min(
                12,  // Maximum 12 reps
                $repAdjustment
            )
        );
    }
}

// Add sliding method to collections if not already present
if (!method_exists(\Illuminate\Support\Collection::class, 'sliding')) {
    \Illuminate\Support\Collection::macro('sliding', function ($size = 2) {
        $count = $this->count();
        if ($count < $size) {
            return collect([]);
        }

        $result = collect();
        for ($i = 0; $i <= $count - $size; $i++) {
            $slice = $this->slice($i, $size);
            $result->push($slice);
        }
        return $result;
    });
}

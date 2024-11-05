<?php
//////Form request????????????????///////////////
namespace Modules\Exercise\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Exercise\Models\Set;

class LastedUpcomingliftingController extends Controller
{
    public function getLastAndUpcoming(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'exercise_id' => 'required|integer',
        ]);

        // Fetch the latest set for the specified user and exercise
        $latestSet = Set::whereHas('userExercise', function($query) use ($request) {
                $query->where('user_id', $request->user_id)
                      ->where('exercise_id', $request->exercise_id);
            })
            ->orderBy('created_at', 'desc')
            ->first();

        // If no set found, return a default response
        if (!$latestSet) {
            return response()->json([
                'message' => 'No sets found for this exercise and user.'
            ], 404);
        }

        // Calculate upcoming values
        $upcomingWeight = $latestSet->weight + 5;
        $upcomingReps = $latestSet->reps + 2;

        return response()->json([
            'last' => [
                'weight' => $latestSet->weight,
                'reps' => $latestSet->reps,
            ],
            'upcoming' => [
                'weight' => $upcomingWeight,
                'reps' => $upcomingReps,
            ]
        ]);
    }
    
}

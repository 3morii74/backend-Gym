<?php

namespace Modules\Exercise\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Modules\Exercise\Http\Requests\DeleteUserExerciseRequest;
use Modules\Exercise\Http\Requests\IndexUserExerciseRequest;
use Modules\Exercise\Http\Requests\storeUserExerciseRequest;
use Modules\Exercise\Http\Requests\UpdateUserExerciseRequest;
use Modules\Exercise\Models\ExerciseSystemDefault;
use Modules\Exercise\Models\UserExercise;
use Modules\Exercise\Models\UserSystemExercise;
use Modules\User\Models\User;

class UserExerciseController extends Controller
{
    use ApiResponseTrait;

    // Method to get all user exercises
    public function index()
    {
        try {
            // Fetch all users with their associated UserSystemExercises and UserExercises
            $users = User::with(['exerciseSystems.defaultExercises'])->get();

            return $this->apiResponse($users, 200, "All users with their System Exercises and User Exercises retrieved successfully.");
        } catch (\Exception $e) {
            return $this->apiResponse(null, 500, "Error retrieving users: " . $e->getMessage());
        }
    }

    // Method to get exercises for a specific user
    public function indexForUser(IndexUserExerciseRequest $request)
    {
        try {
            $exercises = User::with(['exerciseSystems.defaultExercises'])
                ->where('id', $request->user_id)
                ->get();

            return $this->apiResponse($exercises, 200, "Exercises for User ID {$request->user_id} retrieved successfully.");
        } catch (\Exception $e) {
            return $this->apiResponse(null, 500, "Error retrieving exercises for user: " . $e->getMessage());
        }
    }

    public function store(storeUserExerciseRequest $request)
    {
        try {
            // Check if the UserSystemExercise already exists
            $userSystemExercise = UserSystemExercise::where('user_id', $request->user_id)
                ->where('exercise_system_default_id', $request->system_id)
                ->whereNull('deleted_at') // Only consider non-deleted rows
                ->first();

            // If it doesn't exist, create a new UserSystemExercise
            if (!$userSystemExercise) {
                $userSystemExercise = UserSystemExercise::create([
                    'user_id' => $request->user_id,
                    'exercise_system_default_id' => $request->system_id,
                ]);
            }

            // Attach exercises to the user_system_exercise
            foreach ($request->exercise_ids as $exerciseId) {
                // Check if the exercise is already attached
                $existingUserExercise = UserExercise::where('user_id', $request->user_id)
                    ->where('exercise_id', $exerciseId)
                    ->where('user_system_exercise_id', $userSystemExercise->id)
                    ->first();

                // Only create a new UserExercise if it does not exist
                if (!$existingUserExercise) {
                    UserExercise::create([
                        'user_id' => $request->user_id,
                        'exercise_id' => $exerciseId,
                        'user_system_exercise_id' => $userSystemExercise->id,
                    ]);
                }
            }

            return $this->apiResponse(null, 200, "Exercises attached successfully!");
        } catch (\Exception $e) {
            return $this->apiResponse(null, 500, "Error attaching exercises: " . $e->getMessage());
        }
    }

    public function update(UpdateUserExerciseRequest $request)
    {
        try {
            $userSystemExercise = UserSystemExercise::findOrFail($request->id);

            // Detach current exercises
            UserExercise::where('user_system_exercise_id', $userSystemExercise->id)->delete();

            // Attach new exercises
            foreach ($request->exercise_ids as $exerciseId) {
                UserExercise::create([
                    'user_id' => $userSystemExercise->user_id,
                    'exercise_id' => $exerciseId,
                    'user_system_exercise_id' => $userSystemExercise->id,
                ]);
            }

            return $this->apiResponse(null, 200, "User's exercises updated successfully.");
        } catch (\Exception $e) {
            return $this->apiResponse(null, 500, "Error updating user's exercises: " . $e->getMessage());
        }
    }

    public function detach(DeleteUserExerciseRequest $request)
    {
        try {
            // Find the UserSystemExercise using user_id and exercise_system_default_id
            $userSystemExercise = UserSystemExercise::where('user_id', $request->user_id)
                ->where('exercise_system_default_id', $request->system_id)
                ->first();

            if (!$userSystemExercise) {
                return $this->apiResponse(null, 404, "System not found.");
            }

            // Loop through each exercise_id and delete the corresponding user exercise
            foreach ($request->exercise_ids as $exerciseId) {
                $userExercise = UserExercise::where('user_id', $request->user_id)
                    ->where('exercise_id', $exerciseId)
                    ->where('user_system_exercise_id', $userSystemExercise->id)
                    ->first();

                if ($userExercise) {
                    $userExercise->delete(); // Perform delete
                }
            }

            // Check if any exercises remain for the system in user_exercises table
            $remainingExercises = UserExercise::where('user_system_exercise_id', $userSystemExercise->id)->count();

            // If no exercises are left, delete the user system exercise record
            if ($remainingExercises === 0) {
                $userSystemExercise->delete();
            }

            return $this->apiResponse(null, 200, "Exercises detached successfully.");
        } catch (\Exception $e) {
            return $this->apiResponse(null, 500, "Error detaching exercises: " . $e->getMessage());
        }
    }
}

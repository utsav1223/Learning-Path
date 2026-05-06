<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // CREATE PROFILE
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bio' => 'nullable|string',
            'education_level' => 'nullable|string',
            'skill_level' => 'required|string',
            'interests' => 'required|array',
            'learning_goal' => 'required|string',
            'preferred_language' => 'nullable|string',
            'daily_learning_time' => 'nullable|integer'
        ]);

        $validated['user_id'] = auth()->id();

        $profile = Profile::create($validated);

        return response()->json([
            'message' => 'Profile created successfully',
            'profile' => $profile
        ], 201);
    }

    // GET PROFILE
    public function show()
    {
        $profile = auth()->user()->profile;

        return response()->json([
            'profile' => $profile
        ]);
    }

    // UPDATE PROFILE
    public function update(Request $request)
    {
        $profile = auth()->user()->profile;

        $profile->update($request->all());

        return response()->json([
            'message' => 'Profile updated successfully',
            'profile' => $profile
        ]);
    }
}
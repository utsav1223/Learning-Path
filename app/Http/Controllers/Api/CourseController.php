<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'difficulty_level' => ['required', 'in:Beginner,Intermediate,Advanced'],
            'estimated_hours' => ['required', 'integer', 'min:1'],
            'thumbnail' => ['nullable', 'string'],
        ]);

        $course = Course::create($validated)->load('category');

        return response()->json([
            'message' => 'Course created successfully',
            'course' => $course,
        ], 201);
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'difficulty_level' => ['nullable', 'in:Beginner,Intermediate,Advanced'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $courses = Course::with('category')
            ->when($validated['category_id'] ?? null, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($validated['difficulty_level'] ?? null, function ($query, $difficultyLevel) {
                $query->where('difficulty_level', $difficultyLevel);
            })
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return response()->json([
            'courses' => $courses,
        ]);
    }
}

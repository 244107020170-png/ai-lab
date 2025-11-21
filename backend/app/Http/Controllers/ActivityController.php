<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    // GET all activities (public)
    public function index()
    {
        return response()->json(
            Activity::orderBy('published_at', 'desc')->get()
        );
    }

    // GET single activity (public)
    public function show($id)
    {
        return response()->json(Activity::findOrFail($id));
    }

    // CREATE (admin)
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'label' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'full_description' => 'nullable|string',
            'published_at' => 'nullable|date',
            'thumbnail_image' => 'nullable|string',
            'banner_image' => 'nullable|string',
        ]);

        $activity = Activity::create($data);

        return response()->json([
            'success' => true,
            'data' => $activity
        ]);
    }
}

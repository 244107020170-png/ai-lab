<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    // GET ALL ACTIVITIES (public)
    public function index()
{
    $items = Activity::where('status', 'published')
        ->whereNotNull('published_at')
        ->orderBy('published_at', 'desc')
        ->get()
        ->map(function ($a) {
            // FIX path supaya gambar bisa diakses frontend
            if ($a->thumbnail_image) {
                $a->thumbnail_image = asset('storage/' . $a->thumbnail_image);
            }
            if ($a->banner_image) {
                $a->banner_image = asset('storage/' . $a->banner_image);
            }
            return $a;
        });

    return response()->json($items);
}

    // GET SINGLE ACTIVITY (public)
    public function show($id)
{
    $a = Activity::findOrFail($id);

    if ($a->status !== 'published') {
        return response()->json([
            'error' => 'This activity is not published.'
        ], 403);
    }

    return response()->json($a);
}


    // CREATE (Admin)
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'              => 'required|string|max:255',
            'label'              => 'nullable|string|max:255',
            'short_description'  => 'nullable|string',
            'full_description'   => 'nullable|string',
            'published_at'       => 'nullable|date',
            'document_link'      => 'nullable|string|max:500',
            'status'             => 'required|in:published,progress,cancelled',
            'thumbnail_image'    => 'nullable|file|mimes:jpg,jpeg,png|max:4096',
            'banner_image'       => 'nullable|file|mimes:jpg,jpeg,png|max:4096',
        ]);

        // HANDLE IMAGE UPLOAD
        if ($request->hasFile('thumbnail_image')) {
            $data['thumbnail_image'] = $request->file('thumbnail_image')
                ->store('activities/thumbnails', 'public');
        }

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')
                ->store('activities/banners', 'public');
        }

        $activity = Activity::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Activity created successfully',
            'data' => $activity
        ]);
    }


    // UPDATE (Admin)
    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $data = $request->validate([
            'title'              => 'sometimes|required|string|max:255',
            'label'              => 'nullable|string|max:255',
            'short_description'  => 'nullable|string',
            'full_description'   => 'nullable|string',
            'published_at'       => 'nullable|date',
            'document_link'      => 'nullable|string|max:500',
            'status'             => 'required|in:published,progress,cancelled',
            'thumbnail_image'    => 'nullable|file|mimes:jpg,jpeg,png|max:4096',
            'banner_image'       => 'nullable|file|mimes:jpg,jpeg,png|max:4096',
        ]);

        // NEW THUMBNAIL
        if ($request->hasFile('thumbnail_image')) {
            // delete old file if exists
            if ($activity->thumbnail_image) {
                Storage::disk('public')->delete($activity->thumbnail_image);
            }
            $data['thumbnail_image'] = $request->file('thumbnail_image')
                ->store('activities/thumbnails', 'public');
        }

        // NEW BANNER
        if ($request->hasFile('banner_image')) {
            if ($activity->banner_image) {
                Storage::disk('public')->delete($activity->banner_image);
            }
            $data['banner_image'] = $request->file('banner_image')
                ->store('activities/banners', 'public');
        }

        $activity->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Activity updated successfully',
            'data' => $activity
        ]);
    }


    // DELETE (Admin)
    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);

        // delete stored images
        if ($activity->thumbnail_image) {
            Storage::disk('public')->delete($activity->thumbnail_image);
        }
        if ($activity->banner_image) {
            Storage::disk('public')->delete($activity->banner_image);
        }

        $activity->delete();

        return response()->json([
            'success' => true,
            'message' => 'Activity deleted successfully'
        ]);
    }
}

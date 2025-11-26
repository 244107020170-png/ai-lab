<?php

namespace App\Http\Controllers;

use App\Models\VolunteerRegistration;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    // Admin - get all volunteer submissions
    public function index()
    {
        return response()->json(
            VolunteerRegistration::orderBy('created_at', 'desc')->get()
        );
    }

    // User - submit volunteer form
    public function submit(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:200',
            'nickname' => 'nullable|string|max:100',
            'study_program' => 'nullable|string|max:150',
            'semester' => 'nullable|integer',
            'email' => 'nullable|email|max:200',
            'phone' => 'nullable|string|max:50',
            'areas' => 'nullable|array',
            'skills' => 'nullable|string',
            'motivation' => 'nullable|string',
            'availability' => 'nullable|string|max:100',
        ]);

        // Ensure JSON array stored properly
        $data['areas'] = $request->areas ?? [];

        $save = VolunteerRegistration::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Volunteer registration submitted!',
            'data' => $save
        ]);
    }
}

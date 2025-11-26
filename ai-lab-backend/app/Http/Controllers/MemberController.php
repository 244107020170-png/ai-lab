<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    // GET all members
    public function index()
    {
        return Member::orderBy('full_name')->get();
    }

    // GET detail member
    public function show($id)
{
    $member = Member::with([
        'backgrounds',
        'publications',
        'research',
        'ips',
        'ppm',
        'activities'
    ])->findOrFail($id);

    return response()->json($member);
}

    // POST create member (for admin)
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string',
            'role' => 'nullable|string',
            'photo' => 'nullable|string',
            'expertise' => 'nullable|string',
            'description' => 'nullable|string',
            'linkedin' => 'nullable|string',
            'scholar' => 'nullable|string',
            'researchgate' => 'nullable|string',
            'orcid' => 'nullable|string',
        ]);

        $member = Member::create($data);

        return response()->json($member, 201);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabPermitRequest;
use App\Models\AdminAction;
use App\Models\EmailLog;
use Illuminate\Support\Facades\Mail;
//use App\Mail\PermitDecisionMail;
use Illuminate\Support\Facades\Auth;

class PermitController extends Controller
{
    // Submit request (public)
    public function submit(Request $r)
    {
        $v = $r->validate([
            'full_name' => 'required|string|max:200',
            'study_program' => 'nullable|string|max:150',
            'semester' => 'nullable|integer|min:1|max:99',
            'phone' => 'nullable|string|max:50',
            'email' => 'required|email',
            'reason' => 'required|string',
        ]);

        // Optional limit 3 submissions
        $count = LabPermitRequest::where('email', $v['email'])->count();
        if ($count >= 3) {
            return response()->json([
                'success' => false,
                'message' => 'Limit 3 submissions per email reached'
            ], 422);
        }

        $permit = LabPermitRequest::create($v);

        return response()->json([
            'success' => true,
            'message' => 'Submitted successfully',
            'data' => $permit
        ]);
    }

    // Get all (admin)
    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $items = LabPermitRequest::orderBy('submitted_at', 'desc')->paginate(20);
        return response()->json($items);
    }

    // APPROVE
    public function approve($id, Request $r)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $permit = LabPermitRequest::findOrFail($id);

        $permit->status = 'accepted';
        $permit->admin_id = $user->id;
        $permit->admin_notes = $r->note;
        $permit->save();

        // Send email
        //Mail::to($permit->email)->send(
            //new PermitDecisionMail($permit, 'accepted')
        //);

        // Log email
        //EmailLog::create([
          //  'to_email' => $permit->email,
            //'from_email' => config('mail.from.address'),
            //'subject' => 'Your Lab Permit Request — Accepted',
            //'body' => 'Approved by admin',
            //'related_table' => 'lab_permit_requests',
            //'related_id' => $permit->id,
            //'status' => 'sent',
            //'sent_at' => now(),
        //]);

        // Log admin action
        AdminAction::create([
            'admin_id' => $user->id,
            'action_type' => 'approve_permit',
            'target_table' => 'lab_permit_requests',
            'target_id' => $permit->id,
            'note' => $r->note,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permit approved',
            'data' => $permit
        ]);
    }

    // REJECT
    public function reject($id, Request $r)
    {
        $user = Auth::user();
        $user = Auth::user();
if (!$user || $user->role !== 'admin') {
    return response()->json(['message' => 'Unauthorized'], 401);
}


        $permit = LabPermitRequest::findOrFail($id);

        $permit->status = 'rejected';
        $permit->admin_id = $user->id;
        $permit->admin_notes = $r->note;
        $permit->save();

       // Mail::to($permit->email)->send(
         //   new PermitDecisionMail($permit, 'rejected')
        //);

        //EmailLog::create([
          //  'to_email' => $permit->email,
            //'from_email' => config('mail.from.address'),
            //'subject' => 'Your Lab Permit Request — Rejected',
            //'body' => 'Rejected by admin',
            //'related_table' => 'lab_permit_requests',
            //'related_id' => $permit->id,
            //'status' => 'sent',
            //'sent_at' => now(),
        //]);

        AdminAction::create([
            'admin_id' => $user->id,
            'action_type' => 'reject_permit',
            'target_table' => 'lab_permit_requests',
            'target_id' => $permit->id,
            'note' => $r->note,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permit rejected',
            'data' => $permit
        ]);
    }
}

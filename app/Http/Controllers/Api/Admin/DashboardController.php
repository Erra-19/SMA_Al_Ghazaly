<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'registrations' => [
                'total'    => Registration::count(),
                'pending'  => Registration::where('status', 'pending')->count(),
                'accepted' => Registration::where('status', 'accepted')->count(),
                'rejected' => Registration::where('status', 'rejected')->count(),
            ],
            'payments' => [
                'total'  => Payment::count(),
                'paid'   => Payment::where('status', 'paid')->count(),
                'pending'=> Payment::where('status', 'pending')->count(),
            ],
            'posts'            => Post::count(),
            'users'            => User::count(),
            'unread_messages'  => FormSubmission::where('is_read', 0)->count(),
            'recent_registrations' => Registration::latest()->take(5)
                ->get(['registration_id', 'registration_number', 'full_name', 'status', 'created_at']),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Post;
use App\Models\Registration;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $monthLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        $byMonth = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $byMonth[] = [
                'label' => $monthLabels[$d->month - 1],
                'count' => Registration::whereYear('created_at', $d->year)
                              ->whereMonth('created_at', $d->month)
                              ->count(),
            ];
        }

        return response()->json([
            'registrations' => [
                'total'           => Registration::count(),
                'submitted'       => Registration::where('status', 'submitted')->count(),
                'document_review' => Registration::where('status', 'document_review')->count(),
                'verified'        => Registration::where('status', 'verified')->count(),
                'accepted'        => Registration::where('status', 'accepted')->count(),
                'rejected'        => Registration::where('status', 'rejected')->count(),
            ],
            'payments' => [
                'total'        => Payment::count(),
                'paid'         => Payment::where('status', 'paid')->count(),
                'pending'      => Payment::where('status', 'pending')->count(),
                'needs_verify' => Payment::whereNotNull('proof_url')->where('status', 'pending')->count(),
            ],
            'posts'                  => Post::count(),
            'unread_messages'        => Message::where('is_read', 0)->count(),
            'registrations_by_month' => $byMonth,
            'recent_registrations'   => Registration::latest()->take(5)
                ->get(['registration_id', 'registration_number', 'student_name', 'status', 'created_at']),
            'pending_payments'       => Payment::whereNotNull('proof_url')
                ->where('status', 'pending')
                ->with('registration:registration_id,student_name,registration_number')
                ->latest()->take(4)
                ->get(['payment_id', 'registration_id', 'amount', 'payment_type', 'created_at']),
            'upcoming_events'        => AcademicCalendar::where('is_published', 1)
                ->where('start_date', '>=', today())
                ->orderBy('start_date')
                ->take(4)
                ->get(['calendar_id', 'title', 'start_date', 'category', 'color']),
        ]);
    }
}

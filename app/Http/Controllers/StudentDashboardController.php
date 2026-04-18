<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\Reservation;
use Illuminate\View\View;

class StudentDashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $student = $user->student;

        $recentReservations = Reservation::with(['foodItem.cafeteria'])
            ->where('student_id', $student->id)
            ->latest()
            ->take(5)
            ->get();

        $activeReservations = Reservation::with(['foodItem.cafeteria'])
            ->where('student_id', $student->id)
            ->where('status', 'pending')
            ->count();

        $completedReservations = Reservation::where('student_id', $student->id)
            ->where('status', 'completed')
            ->count();

        $availableItems = FoodItem::active()->count();

        return view('student.dashboard', compact(
            'recentReservations',
            'activeReservations',
            'completedReservations',
            'availableItems'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\Reservation;
use App\Services\AnalyticsService;
use Illuminate\View\View;

class StaffDashboardController extends Controller
{
    public function index(AnalyticsService $analyticsService): View
    {
        $user = auth()->user();
        $staff = $user->staffProfile;
        $cafeteria = $staff->cafeteria;

        $totalItems = FoodItem::where('cafeteria_id', $cafeteria->id)->count();
        $activeItems = FoodItem::where('cafeteria_id', $cafeteria->id)->active()->count();

        $foodItemIds = FoodItem::where('cafeteria_id', $cafeteria->id)->pluck('id');

        $pendingReservations = Reservation::whereIn('food_item_id', $foodItemIds)
            ->where('status', 'pending')
            ->count();

        $completedToday = Reservation::whereIn('food_item_id', $foodItemIds)
            ->where('status', 'completed')
            ->whereDate('pickup_time', today())
            ->count();

        $recentReservations = Reservation::with(['student.user', 'foodItem'])
            ->whereIn('food_item_id', $foodItemIds)
            ->latest()
            ->take(10)
            ->get();

        $analytics = $analyticsService->generateWeeklySummary($cafeteria);

        return view('staff.dashboard', compact(
            'cafeteria',
            'totalItems',
            'activeItems',
            'pendingReservations',
            'completedToday',
            'recentReservations',
            'analytics'
        ));
    }
}

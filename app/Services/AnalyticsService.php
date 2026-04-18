<?php

namespace App\Services;

use App\Models\Analytic;
use App\Models\Cafeteria;
use App\Models\FoodItem;
use App\Models\Reservation;
use Carbon\Carbon;

class AnalyticsService
{
    public function generateWeeklySummary(Cafeteria $cafeteria, ?Carbon $weekStart = null): Analytic
    {
        $weekStart = $weekStart ?? now()->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();

        $foodItemIds = $cafeteria->foodItems()->pluck('id');

        $totalListings = FoodItem::where('cafeteria_id', $cafeteria->id)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->count();

        $reservations = Reservation::whereIn('food_item_id', $foodItemIds)
            ->whereBetween('created_at', [$weekStart, $weekEnd]);

        $totalReservations = $reservations->count();
        $completedPickups = (clone $reservations)->where('status', 'completed')->count();
        $totalRevenue = (clone $reservations)->where('status', 'completed')->sum('total_price');

        $mostPopular = Reservation::whereIn('food_item_id', $foodItemIds)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->selectRaw('food_item_id, SUM(quantity) as total_qty')
            ->groupBy('food_item_id')
            ->orderByDesc('total_qty')
            ->first();

        $mostPopularName = $mostPopular
            ? FoodItem::find($mostPopular->food_item_id)?->title
            : null;

        return Analytic::updateOrCreate(
            [
                'cafeteria_id' => $cafeteria->id,
                'week_start' => $weekStart->toDateString(),
                'week_end' => $weekEnd->toDateString(),
            ],
            [
                'total_listings' => $totalListings,
                'total_reservations' => $totalReservations,
                'completed_pickups' => $completedPickups,
                'total_revenue' => $totalRevenue,
                'most_popular_item' => $mostPopularName,
            ]
        );
    }
}

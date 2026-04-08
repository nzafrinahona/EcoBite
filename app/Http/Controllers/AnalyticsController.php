<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function __construct(
        private AnalyticsService $analyticsService
    ) {}

    public function index(): View
    {
        $staff = auth()->user()->staffProfile;
        $cafeteria = $staff->cafeteria;

        $analytics = $cafeteria->analytics()
            ->orderByDesc('week_start')
            ->paginate(10);

        $currentWeek = $this->analyticsService->generateWeeklySummary($cafeteria);

        return view('staff.analytics', compact('analytics', 'currentWeek', 'cafeteria'));
    }
}

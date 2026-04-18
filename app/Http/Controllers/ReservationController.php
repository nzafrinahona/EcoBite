<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationService $reservationService
    ) {}

    public function index(): View
    {
        $student = auth()->user()->student;

        $reservations = Reservation::with(['foodItem.cafeteria', 'review'])
            ->where('student_id', $student->id)
            ->latest()
            ->paginate(10);

        return view('student.reservations', compact('reservations'));
    }

    public function store(StoreReservationRequest $request): RedirectResponse
    {
        $student = auth()->user()->student;

        try {
            $reservation = $this->reservationService->createReservation(
                $student,
                $request->validated('food_item_id'),
                $request->validated('quantity')
            );

            return redirect()->route('student.reservations.index')
                ->with('success', "Reservation confirmed! Pickup code: {$reservation->pickup_code}");
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(Reservation $reservation): View
    {
        $reservation->load(['foodItem.cafeteria', 'student.user', 'review']);
        return view('student.reservation-detail', compact('reservation'));
    }

    public function cancel(Reservation $reservation): RedirectResponse
    {
        try {
            $this->reservationService->cancelReservation($reservation);
            return back()->with('success', 'Reservation cancelled successfully. Stock has been restored.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Staff: view all reservations for their cafeteria
    public function staffIndex(): View
    {
        $staff = auth()->user()->staffProfile;
        $cafeteria = $staff->cafeteria;

        $foodItemIds = $cafeteria->foodItems()->pluck('id');

        $reservations = Reservation::with(['student.user', 'foodItem'])
            ->whereIn('food_item_id', $foodItemIds)
            ->latest()
            ->paginate(15);

        return view('staff.reservations', compact('reservations'));
    }

    // Staff: verify pickup code
    public function verifyPickup(Request $request): View|RedirectResponse
    {
        if ($request->isMethod('get')) {
            return view('staff.verify-pickup');
        }

        $request->validate(['pickup_code' => ['required', 'string', 'size:5']]);

        $reservation = $this->reservationService->verifyPickupCode($request->pickup_code);

        if (!$reservation) {
            return back()->with('error', 'Invalid or already used pickup code.');
        }

        return view('staff.verify-pickup', compact('reservation'));
    }

    // Staff: confirm pickup
    public function confirmPickup(Reservation $reservation): RedirectResponse
    {
        try {
            $this->reservationService->completeReservation($reservation);
            return redirect()->route('staff.verify-pickup')
                ->with('success', 'Pickup completed successfully!');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

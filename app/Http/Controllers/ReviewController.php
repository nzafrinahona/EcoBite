<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReviewRequest;
use App\Models\Reservation;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $reservation = Reservation::findOrFail($data['reservation_id']);

        if (!$reservation->isCompleted()) {
            return back()->with('error', 'You can only review completed reservations.');
        }

        if ($reservation->review) {
            return back()->with('error', 'You have already reviewed this reservation.');
        }

        $student = auth()->user()->student;
        if ($reservation->student_id !== $student->id) {
            abort(403);
        }

        Review::create($data);

        return back()->with('success', 'Review submitted successfully!');
    }
}

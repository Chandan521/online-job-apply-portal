<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Job $job)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'job_id' => $job->id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return back()->with('success', 'Thank you for your feedback!');
    }
}

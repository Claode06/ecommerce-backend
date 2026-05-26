<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $reviews = Review::with(['user', 'product', 'orderItem'])
            ->when($request->filled('product_id'), fn ($q) => $q->where('product_id', $request->input('product_id')))
            ->when($request->filled('rating'), fn ($q) => $q->where('rating', $request->input('rating')))
            ->when($request->filled('visibility'), fn ($q) => $q->where('is_visible', $request->input('visibility')))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggle(Review $review)
    {
        $review->update(['is_visible' => ! $review->is_visible]);

        Log::info('Review visibility toggled', [
            'admin_id' => auth('admin')->id(),
            'review_id' => $review->id,
            'is_visible' => $review->is_visible,
        ]);

        return back()->with('success', 'Status ulasan berhasil diubah.');
    }
}

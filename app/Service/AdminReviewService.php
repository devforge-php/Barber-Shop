<?php

namespace App\Service;

use App\Models\Review;
use Illuminate\Support\Facades\Cache;

class AdminReviewService
{
    public function get()
    {
        return Cache::rememberForever('review_all', function () {
            return Review::with('barber')->get(); // <- Eager load 'barber'
        });
    }

    public function find($id)
    {
        return Cache::rememberForever("review_$id", function () use ($id) {
            return Review::with('barber')->find($id); // <- Eager load 'barber'
        });
    }

    public function forgetCaches($id)
    {
        Cache::forget("review_$id");
        Cache::forget('review_all');
    }

    public function delete($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();
        $this->forgetCaches($id); // <- Cache o'chirilsin
    }
}

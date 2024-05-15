<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\Review;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::whereHas('item', function($query){
            return $query->where('store_id', Helpers::get_store_id());
        })->latest()->paginate(config('default_pagination'));
        return view('vendor-views.review.index', compact('reviews'));
    }

    public function update_reply(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|max:255',
        ]);

        $review = Review::findOrFail($id);
        $review->reply = $request->reply;
        $review->store_id = Helpers::get_store_id();
        $review->save();

        Toastr::success(translate('messages.review_reply_updated'));
        return redirect()->route('vendor.reviews');
    }
}

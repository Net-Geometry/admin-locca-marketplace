<?php

namespace Modules\Rental\Http\Controllers\Api\User;


use App\Models\Store;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\CentralLogics\StoreLogic;

use Illuminate\Routing\Controller;
use Modules\Rental\Entities\Vehicle;


class VehicleReviewController extends Controller
{

    public function __construct(private Vehicle $vehicle, private Helpers $helpers)
    {
        $this->vehicle = $vehicle;
        $this->helpers = $helpers;
    }

    // public function submit_product_review(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'item_id' => 'required',
    //         'order_id' => 'required',
    //         'rating' => 'required|numeric|max:5',
    //     ]);

    //     $order = Order::find($request->order_id);
    //     if (isset($order) == false) {
    //         $validator->errors()->add('order_id', translate('messages.order_data_not_found'));
    //     }

    //     $item = Item::find($request->item_id);
    //     if (isset($order) == false) {
    //         $validator->errors()->add('item_id', translate('messages.item_not_found'));
    //     }

    //     $multi_review = Review::where(['item_id' => $request->item_id, 'user_id' => $request->user()->id, 'order_id'=>$request->order_id])->first();
    //     if (isset($multi_review)) {
    //         return response()->json([
    //             'errors' => [
    //                 ['code'=>'review','message'=> translate('messages.already_submitted')]
    //             ]
    //         ], 403);
    //     } else {
    //         $review = new Review;
    //     }

    //     if ($validator->errors()->count() > 0) {
    //         return response()->json(['errors' => Helpers::error_processor($validator)], 403);
    //     }

    //     $image_array = [];
    //     if (!empty($request->file('attachment'))) {
    //         foreach ($request->file('attachment') as $image) {
    //             if ($image != null) {
    //                 if (!Storage::disk('public')->exists('review')) {
    //                     Storage::disk('public')->makeDirectory('review');
    //                 }
    //                 array_push($image_array, Storage::disk('public')->put('review', $image));
    //             }
    //         }
    //     }

    //     $order?->OrderReference?->update([
    //         'is_reviewed' => 1
    //     ]);

    //     $review->user_id = $request->user()->id;
    //     $review->item_id = $request->item_id;
    //     $review->order_id = $request->order_id;
    //     $review->module_id = $order->module_id;
    //     $review->comment = $request?->comment;
    //     $review->rating = $request->rating;
    //     $review->attachment = json_encode($image_array);
    //     $review->save();

    //     if($item->store)
    //     {
    //         $store_rating = StoreLogic::update_store_rating($item->store->rating, (int)$request->rating);
    //         $item->store->rating = $store_rating;
    //         $item->store->save();
    //     }

    //     $item->rating = ProductLogic::update_rating($item->rating, (int)$request->rating);
    //     $item->avg_rating = ProductLogic::get_avg_rating(json_decode($item->rating, true));
    //     $item->save();
    //     $item->increment('rating_count');

    //     return response()->json(['message' => translate('messages.review_submited_successfully')], 200);
    // }


}

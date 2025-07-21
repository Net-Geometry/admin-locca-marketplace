<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\Helpers;
use App\Scopes\ZoneScope;
use App\CentralLogics\OrderLogic;

class EasyParcelWebhookController extends Controller
{
    public function trackingHook(Request $request)
    {
        $payload = $request->input('payload');

       
        if (!$payload || !isset($payload['awb'], $payload['status_code'], $payload['event_date'])) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $order = Order::withoutGlobalScope(ZoneScope::class)
            ->with(['store', 'customer', 'delivery_man', 'transaction'])
            ->where('awb_no', $payload['awb'])
            ->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        // EasyParcel status code => Internal system status mapping
        $statusMap = [
            0  => 'canceled',    // Cancel
            2  => 'confirmed',   // To Be Collected
            3  => 'picked_up',   // Collected
            4  => 'handover',    // In Transit
            5  => 'delivered',   // Delivered
            6  => 'canceled',    // Returned (treat as canceled)
            7  => 'processing',  // Schedule In Arrangement
            8  => 'processing',  // Delivery On Hold
            10 => 'canceled',    // Cancel by admin
            11 => 'accepted',    // Pending drop off
            12 => 'accepted',    // Parcel dropoff
            13 => 'accepted',    // Parcel arrived
            14 => 'processing',  // On Hold
        ];
        

        $statusCode = $payload['status_code'];
        $newStatus = $statusMap[$statusCode] ?? null;

        if (!$newStatus) {
            return response()->json(['message' => 'Unhandled status code'], 422);
        }

        $eventDate = date('Y-m-d H:i:s', strtotime($payload['event_date']));

        $order->order_status = $newStatus;
        $order->{$newStatus} = $eventDate;

     
        if ($newStatus === 'picked_up' && empty($order->processing_time)) {
            $order->processing_time = $order->store && $order->store->delivery_time
                ? explode('-', $order->store->delivery_time)[0]
                : 0;
        }

 
        if ($newStatus === 'delivered') {
            $order->payment_status = 'paid';

            if (!$order->transaction) {
                OrderLogic::create_transaction(
                    $order,
                    $order->delivery_man_id ? 'deliveryman' : 'admin',
                    null
                );
            } else {
                $order->transaction->update(['delivery_man_id' => $order->delivery_man_id]);
            }

            OrderLogic::update_unpaid_order_payment($order->id, $order->payment_method);
        }

        $order->save();

      
        $data = [
            'title' => translate('messages.order_status_updated'),
            'description' => translate('messages.Your_order_status_has_been_updated_to') . ' ' . ucfirst($order->order_status),
            'order_id' => $order->id,
            'image' => '',
            'type' => 'order_status',
            'order_status' => $order->order_status,
        ];

        if ($order->customer?->cm_firebase_token) {
            Helpers::send_push_notif_to_device($order->customer->cm_firebase_token, $data);

            DB::table('user_notifications')->insert([
                'data' => json_encode($data),
                'user_id' => $order->user_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json(['message' => 'Order status updated'], 200);
    }
}

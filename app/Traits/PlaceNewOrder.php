<?php

namespace App\Traits;

use App\Models\Cart;
use App\Models\Item;
use App\Models\User;
use App\Models\Zone;
use App\Models\Order;
use App\Models\Store;
use App\Models\Coupon;
use App\Models\DMVehicle;
use App\Models\OrderDetail;
use App\Models\ItemCampaign;
use Illuminate\Http\Request;
use App\CentralLogics\Helpers;
use App\Models\ParcelCategory;
use App\Models\BusinessSetting;
use App\CentralLogics\OrderLogic;
use App\CentralLogics\CouponLogic;
use Illuminate\Support\Facades\DB;
use App\CentralLogics\ProductLogic;
use App\Mail\OrderVerificationMail;
use App\CentralLogics\CustomerLogic;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use MatanYadaev\EloquentSpatial\Objects\Point;
use  App\Mail\CustomerRegistration;
use App\Mail\PlaceOrder;
use App\Models\AddOn;

trait PlaceNewOrder
{

    public function new_place_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'order_amount' => 'required',
            'payment_method' => 'required|in:cash_on_delivery,digital_payment,wallet,offline_payment',
            'order_type' => 'required|in:take_away,delivery,parcel',
            'store_id' => 'required_unless:order_type,parcel',
            'distance' => 'required_unless:order_type,take_away',
            'address' => 'required_unless:order_type,take_away',
            'longitude' => 'required_unless:order_type,take_away',
            'latitude' => 'required_unless:order_type,take_away',
            'parcel_category_id' => 'required_if:order_type,parcel',
            'receiver_details' => 'required_if:order_type,parcel',
            'charge_payer' => 'required_if:order_type,parcel|in:sender,receiver',
            'dm_tips' => 'nullable|numeric',
            'guest_id' => $request->user ? 'nullable' : 'required',
            'contact_person_name' => $request->user ? 'nullable' : 'required',
            'contact_person_number' => $request->user ? 'nullable' : 'required',
            'contact_person_email' => $request->user ? 'nullable' : 'required',
            'password' => $request->create_new_user ? ['required', Password::min(8)] : 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $createNewUser =  $this->createNewUser($request);

        if (data_get($createNewUser, 'newUser') === true) {
            $request->is_guest = 0;
            $request->user = data_get($createNewUser, 'user');
        } elseif (data_get($createNewUser, 'status_code') === 403) {
            return response()->json([
                'errors' => [
                    ['code' => data_get($createNewUser, 'code'), 'message' => data_get($createNewUser, 'message')]
                ]
            ], data_get($createNewUser, 'status_code'));
        }

        $validationCheck =  $this->validationCheck($request);
        if (data_get($validationCheck, 'status_code') === 403) {
            return response()->json([
                'errors' => [
                    ['code' => data_get($validationCheck, 'code'), 'message' => data_get($validationCheck, 'message')]
                ]
            ], data_get($validationCheck, 'status_code'));
        }

        $schedule_at = $request->schedule_at ? \Carbon\Carbon::parse($request->schedule_at) : now();
        $zoneAndStore = $this->getZoneAndStore($request, $schedule_at);

        if (data_get($zoneAndStore, 'status_code') === 403) {
            return response()->json([
                'errors' => [
                    ['code' => data_get($zoneAndStore, 'code'), 'message' => data_get($zoneAndStore, 'message')]
                ]
            ], data_get($zoneAndStore, 'status_code'));
        }

        $store = $zoneAndStore['store'];
        $zone = $zoneAndStore['zone'];

        $zoneAndStoreValidationCheck = $this->zoneAndStoreValidationCheck($request, $schedule_at, $zone, $store);
        if (data_get($zoneAndStoreValidationCheck, 'status_code') === 403) {
            return response()->json([
                'errors' => [
                    ['code' => data_get($zoneAndStoreValidationCheck, 'code'), 'message' => data_get($zoneAndStoreValidationCheck, 'message')]
                ]
            ], data_get($zoneAndStoreValidationCheck, 'status_code'));
        }

        $coupon = null;
        $coupon_created_by = null;
        $delivery_charge = null;
        $free_delivery_by = null;

        if ($request->order_type !== 'parcel') {

            $couponData = $this->getCouponData($request);
            if (data_get($couponData, 'status_code') === 403) {
                return response()->json([
                    'errors' => [
                        ['code' => data_get($couponData, 'code'), 'message' => data_get($couponData, 'message')]
                    ]
                ], data_get($couponData, 'status_code'));
            } else {
                $coupon = data_get($couponData, 'coupon');
                $coupon_created_by = data_get($couponData, 'coupon_created_by');
                $delivery_charge = data_get($couponData, 'delivery_charge');
                $free_delivery_by = data_get($couponData, 'free_delivery_by');
            }
        }

        $module_wise_delivery_charge = $zone->modules()->where('modules.id', $request->header('moduleId'))->first();

        $deliveryChargeData = $this->getDeliveryCharge($request, $zone, $store, $module_wise_delivery_charge, $delivery_charge);

        $delivery_charge = data_get($deliveryChargeData, 'delivery_charge', 0);
        $original_delivery_charge = data_get($deliveryChargeData, 'original_delivery_charge', 0);
        $vehicle_id = data_get($deliveryChargeData, 'vehicle_id', null);

        $address = [
            'contact_person_name' => $request->contact_person_name ? $request->contact_person_name : ($request->user ? $request->user->f_name . ' ' . $request->user->l_name : ''),
            'contact_person_number' => $request->contact_person_number ? $request->contact_person_number : ($request->user ? $request->user->phone : ''),
            'contact_person_email' => $request->contact_person_email ? $request->contact_person_email : ($request->user ? $request->user->email : ''),
            'address_type' => $request->address_type ? $request->address_type : 'Delivery',
            'address' => $request?->address ?? '',
            'floor' => $request?->floor ?? '',
            'road' => $request?->road ?? '',
            'house' => $request?->house ?? '',
            'longitude' => (string)$request->longitude,
            'latitude' => (string)$request->latitude,
        ];

        $total_addon_price = 0;
        $product_price = 0;
        $store_discount_amount = 0;
        $flash_sale_vendor_discount_amount = 0;
        $flash_sale_admin_discount_amount = 0;
        $store_discount_amount = 0;
        $product_data = [];

        $order_details = [];


        $lastId = Order::max('id') ?? 99999;
        $order = new Order();
        $order->id = $lastId + 1;


        $order_status = 'pending';
        if (($request->partial_payment && $request->payment_method != 'offline_payment') || $request->payment_method == 'wallet') {
            $order_status = 'confirmed';
        }

        $order->user_id = $request->user ? $request->user->id : $request['guest_id'];
        $order->order_amount = $request['order_amount'];
        $order->payment_status = ($request->partial_payment ? 'partially_paid' : ($request['payment_method'] == 'wallet' ? 'paid' : 'unpaid'));
        $order->order_status = $order_status;
        $order->coupon_code = $request['coupon_code'];
        $order->payment_method = $request->partial_payment ? 'partial_payment' : $request->payment_method;
        $order->transaction_reference = null;
        $order->order_note = $request['order_note'];
        $order->unavailable_item_note = $request['unavailable_item_note'];
        $order->delivery_instruction = $request['delivery_instruction'];
        $order->order_type = $request['order_type'];
        $order->store_id = $request['store_id'];
        $order->delivery_charge = round($delivery_charge, config('round_up_to_digit')) ?? 0;
        $order->original_delivery_charge = round($original_delivery_charge, config('round_up_to_digit'));
        $order->delivery_address = json_encode($address);
        $order->schedule_at = $schedule_at;
        $order->scheduled = $request->schedule_at ? 1 : 0;
        $order->cutlery = $request->cutlery ? 1 : 0;
        $order->is_guest = $request->user ? 0 : 1;
        $order->otp = rand(1000, 9999);
        $order->zone_id = isset($zone) ? $zone->id : end(json_decode($request->header('zoneId'), true));
        $order->module_id = $request->header('moduleId');
        $order->parcel_category_id = $request->parcel_category_id;
        $order->receiver_details = json_decode($request->receiver_details);

        if ($order_status == 'confirmed') {
            $order->confirmed = now();
        }
        $order->dm_vehicle_id = $vehicle_id;
        $order->pending = now();
        if (!empty($request->file('order_attachment')) && is_array($request->file('order_attachment'))) {
            $img_names = [];
            if (!empty($request->file('order_attachment'))) {
                $images = [];
                foreach ($request->order_attachment as $img) {
                    $image_name = Helpers::upload('order/', 'png', $img);
                    array_push($img_names, ['img' => $image_name, 'storage' => Helpers::getDisk()]);
                }
                $images = $img_names;
            }
        } else {
            $img_names = [];
            if (!empty($request->file('order_attachment'))) {
                $images = [];
                $image_name = Helpers::upload('order/', 'png', $request->file('order_attachment'));
                array_push($img_names, ['img' => $image_name, 'storage' => Helpers::getDisk()]);

                $images = $img_names;
            }
        }
        if (isset($images)) {
            $order->order_attachment = json_encode($images);
        }
        $order->distance = $request->distance;
        $order->created_at = now();
        $order->updated_at = now();
        $order->charge_payer = $request->charge_payer;

        //Added DM TIPS
        $dm_tips_manage_status = BusinessSetting::where('key', 'dm_tips_status')->first()->value;
        if ($dm_tips_manage_status == 1) {
            $order->dm_tips = $request->dm_tips ?? 0;
        } else {
            $order->dm_tips = 0;
        }

        //Added service charge
        $additional_charge_status = BusinessSetting::where('key', 'additional_charge_status')->first()->value;
        $additional_charge = BusinessSetting::where('key', 'additional_charge')->first()->value;
        if ($additional_charge_status == 1) {
            $order->additional_charge = $additional_charge ?? 0;
        } else {
            $order->additional_charge = 0;
        }

        // extra packaging charge
        $extra_packaging_data = BusinessSetting::where('key', 'extra_packaging_data')->first()?->value ?? '';
        $extra_packaging_data = json_decode($extra_packaging_data, true);
        $order->extra_packaging_amount =  (!empty($extra_packaging_data) && $request?->extra_packaging_amount > 0 && $store && ($extra_packaging_data[$store->module->module_type] == '1') && ($store?->storeConfig?->extra_packaging_status == '1')) ? $store?->storeConfig?->extra_packaging_amount : 0;


        $carts = Cart::where('user_id', $order->user_id)->where('is_guest', $order->is_guest)->where('module_id', $request->header('moduleId'))
            ->when(isset($request->is_buy_now) && $request->is_buy_now == 1 && $request->cart_id, function ($query) use ($request) {
                return $query->where('id', $request->cart_id);
            })
            ->get()->map(function ($data) {
                $data->add_on_ids = json_decode($data->add_on_ids, true);
                $data->add_on_qtys = json_decode($data->add_on_qtys, true);
                $data->variation = json_decode($data->variation, true);
                return $data;
            });

        if (isset($request->is_buy_now) && $request->is_buy_now == 1) {
            $carts = json_decode($request['cart'], true);
        }

        if ($request->order_type !== 'parcel') {

            $order_details = $this->makeOrderDetails($carts, $request, $order, $store);

            if (data_get($order_details, 'status_code') === 403) {
                return response()->json([
                    'errors' => [
                        ['code' => data_get($order_details, 'code'), 'message' => data_get($order_details, 'message')]
                    ]
                ], data_get($order_details, 'status_code'));
            }

            $total_addon_price = $order_details['total_addon_price'];
            $product_price = $order_details['product_price'];
            $store_discount_amount = $order_details['store_discount_amount'];
            $flash_sale_admin_discount_amount = $order_details['flash_sale_admin_discount_amount'];
            $flash_sale_vendor_discount_amount = $order_details['flash_sale_vendor_discount_amount'];
            $product_data = $order_details['product_data'];
            $order_details = $order_details['order_details'];


            $order->discount_on_product_by = 'vendor';
            $store_discount = Helpers::get_store_discount($store);
            if (isset($store_discount)) {
                $order->discount_on_product_by = 'admin';
                if ($product_price + $total_addon_price < $store_discount['min_purchase']) {
                    $store_discount_amount = 0;
                }

                if ($store_discount['max_discount'] != 0 && $store_discount_amount > $store_discount['max_discount']) {
                    $store_discount_amount = $store_discount['max_discount'];
                }
            }
            $coupon_discount_amount = $coupon ? CouponLogic::get_discount($coupon, $product_price + $total_addon_price - $store_discount_amount - $flash_sale_admin_discount_amount - $flash_sale_vendor_discount_amount) : 0;

            $total_price = $product_price + $total_addon_price - $store_discount_amount - $flash_sale_admin_discount_amount - $flash_sale_vendor_discount_amount  - $coupon_discount_amount;

            if ($order->is_guest  == 0 && $order->user_id) {
                $user = User::withcount('orders')->find($order->user_id);
                $discount_data = Helpers::getCusromerFirstOrderDiscount(order_count: $user->orders_count, user_creation_date: $user->created_at,  refby: $user->ref_by, price: $total_price);
                if (data_get($discount_data, 'is_valid') == true &&  data_get($discount_data, 'calculated_amount') > 0) {
                    $total_price = $total_price - data_get($discount_data, 'calculated_amount');
                    $order->ref_bonus_amount = data_get($discount_data, 'calculated_amount');
                }
            }


            $tax = ($store->tax > 0) ? $store->tax : 0;
            $order->tax_status = 'excluded';

            $tax_included = BusinessSetting::where(['key' => 'tax_included'])->first() ?  BusinessSetting::where(['key' => 'tax_included'])->first()->value : 0;
            if ($tax_included ==  1) {
                $order->tax_status = 'included';
            }

            $total_tax_amount = Helpers::product_tax($total_price, $tax, $order->tax_status == 'included');

            $tax_a = $order->tax_status == 'included' ? 0 : $total_tax_amount;

            if ($store->minimum_order > $product_price + $total_addon_price) {
                return response()->json([
                    'errors' => [
                        ['code' => 'order_time', 'message' => translate('messages.you_need_to_order_at_least') . $store->minimum_order . ' ' . Helpers::currency_code()]
                    ]
                ], 406);
            }

            $businessSettings = BusinessSetting::whereIn('key', ['free_delivery_over', 'admin_free_delivery_status', 'admin_free_delivery_option'])->pluck('value', 'key');

            $free_delivery_over = (float) ($businessSettings['free_delivery_over'] ?? 0);
            $admin_free_delivery_status = (int) ($businessSettings['admin_free_delivery_status'] ?? 0);
            $admin_free_delivery_option = $businessSettings['admin_free_delivery_option'] ?? null;


            if ($admin_free_delivery_status === 1) {
                $eligibleAmount = $product_price + $total_addon_price - $coupon_discount_amount - $store_discount_amount - $flash_sale_admin_discount_amount - $flash_sale_vendor_discount_amount;

                if ($admin_free_delivery_option === 'free_delivery_to_all_store' || ($admin_free_delivery_option === 'free_delivery_by_order_amount' && $free_delivery_over > 0  && $eligibleAmount >= $free_delivery_over)) {
                    $order->delivery_charge = 0;
                    $free_delivery_by = 'admin';
                }
            }

            if ($store->free_delivery) {
                $order->delivery_charge = 0;
                $free_delivery_by = 'vendor';
            }

            if ($coupon) {
                if ($coupon->coupon_type == 'free_delivery') {
                    if ($coupon->min_purchase <= $product_price + $total_addon_price - $store_discount_amount - $flash_sale_admin_discount_amount - $flash_sale_vendor_discount_amount) {
                        $order->delivery_charge = 0;
                        $free_delivery_by = $coupon->created_by;
                    }
                }
                $coupon->increment('total_uses');
            }
            $order->coupon_created_by = $coupon_created_by;
            $order->coupon_discount_amount = round($coupon_discount_amount, config('round_up_to_digit'));
            $order->coupon_discount_title = $coupon ? $coupon->title : '';

            $order->store_discount_amount = round($store_discount_amount, config('round_up_to_digit'));
            $order->tax_percentage = $tax;
            $order->total_tax_amount = round($total_tax_amount, config('round_up_to_digit'));
            $order->order_amount = round($total_price + $tax_a + $order->delivery_charge, config('round_up_to_digit'));
            $order->free_delivery_by = $free_delivery_by;
        } else {

            $order->delivery_charge = round($original_delivery_charge, config('round_up_to_digit')) ?? 0;
            $order->original_delivery_charge = round($original_delivery_charge, config('round_up_to_digit'));
            $order->order_amount = round($order->delivery_charge, config('round_up_to_digit'));
        }
        $order->flash_admin_discount_amount = round($flash_sale_admin_discount_amount, config('round_up_to_digit'));
        $order->flash_store_discount_amount = round($flash_sale_vendor_discount_amount, config('round_up_to_digit'));

        //DM TIPS
        $order->order_amount = $order->order_amount + $order->dm_tips + $order->additional_charge + $order->extra_packaging_amount;
        if ($request->payment_method == 'wallet' && $request->user->wallet_balance < $order->order_amount) {
            return response()->json([
                'errors' => [
                    ['code' => 'order_amount', 'message' => translate('messages.insufficient_balance')]
                ]
            ], 203);
        }
        if ($request->partial_payment && $request->user->wallet_balance > $order->order_amount) {
            return response()->json([
                'errors' => [
                    ['code' => 'partial_payment', 'message' => translate('messages.order_amount_must_be_greater_than_wallet_amount')]
                ]
            ], 203);
        }
        if (isset($module_wise_delivery_charge) && $request->payment_method == 'cash_on_delivery' && $module_wise_delivery_charge->pivot->maximum_cod_order_amount && $order->order_amount > $module_wise_delivery_charge->pivot->maximum_cod_order_amount) {
            return response()->json([
                'errors' => [
                    ['code' => 'order_amount', 'message' => translate('messages.amount_crossed_maximum_cod_order_amount')]
                ]
            ], 203);
        }

        try {
            DB::beginTransaction();
            $order->save();
            if ($request->order_type !== 'parcel') {
                if (count($order_details) == 0) {
                    $errors = [];
                    array_push($errors, ['code' => 'order_details', 'message' => translate('messages.You_can_not_place_empty_orders')]);
                    DB::rollBack();
                    return response()->json([
                        'errors' => $errors
                    ], 403);
                }

                foreach ($order_details as $key => $item) {
                    $order_details[$key]['order_id'] = $order->id;

                    if ($store_discount_amount <= 0) {
                        $order_details[$key]['discount_on_item'] = 0;
                    }
                }
                OrderDetail::insert($order_details);
                if (count($product_data) > 0) {
                    foreach ($product_data as $item) {
                        ProductLogic::update_stock($item['item'], $item['quantity'], $item['variant'])->save();
                        ProductLogic::update_flash_stock($item['item'], $item['quantity'])?->save();
                    }
                }
                $store->increment('total_order');
            }
            if (!isset($request->is_buy_now) || (isset($request->is_buy_now) && $request->is_buy_now == 0)) {
                foreach ($carts as $cart) {
                    $cart->delete();
                }
            }
            if ($request->user) {
                $customer = $request->user;
                $customer->zone_id = $order->zone_id;
                $customer->save();
                if ($request->payment_method == 'wallet') CustomerLogic::create_wallet_transaction($order->user_id, $order->order_amount, 'order_place', $order->id);

                if ($request->partial_payment) {
                    if ($request->user->wallet_balance <= 0) {
                        return response()->json([
                            'errors' => [
                                ['code' => 'order_amount', 'message' => translate('messages.insufficient_balance_for_partial_amount')]
                            ]
                        ], 203);
                    }
                    $p_amount = min($request->user->wallet_balance, $order->order_amount);
                    $unpaid_amount = $order->order_amount - $p_amount;
                    $order->partially_paid_amount = $p_amount;
                    $order->save();
                    CustomerLogic::create_wallet_transaction($order->user_id, $p_amount, 'partial_payment', $order->id);
                    OrderLogic::create_order_payment(order_id: $order->id, amount: $p_amount, payment_status: 'paid', payment_method: 'wallet');
                    OrderLogic::create_order_payment(order_id: $order->id, amount: $unpaid_amount, payment_status: 'unpaid', payment_method: $request->payment_method);
                }
            }
            if ($order->is_guest  == 0 && $order->user_id) {
                $this->createCashBackHistory($order->order_amount, $order->user_id, $order->id);
            }

            DB::commit();

            $this->sentOrderPlaceNotification($request, $order, $store);

            return response()->json([
                'message' => translate('messages.order_placed_successfully'),
                'order_id' => $order->id,
                'total_ammount' => $order->order_amount,
                'status' => $order->order_status,
                'created_at' => $order->created_at,
                'user_id' => (int) $order->user_id,
            ], 200);
        } catch (\Exception $exception) {
            info([$exception->getFile(), $exception->getLine(), $exception->getMessage()]);
            DB::rollBack();
            return response()->json([$exception], 403);
        }

        return response()->json([
            'errors' => [
                ['code' => 'order_time', 'message' => translate('messages.failed_to_place_order')]
            ]
        ], 403);
    }



    private function createNewUser($request)
    {
        if (!$request->create_new_user) {
            return false;
        }

        $validationError = match (true) {
            !$request->password => [
                'status_code' => 403,
                'message'     => translate('messages.password_is_required'),
                'code'        => 'password',
            ],
            User::where('phone', $request->contact_person_number)->exists() => [
                'status_code' => 403,
                'message'     => translate('messages.phone_already_taken'),
                'code'        => 'phone_person_email',
            ],
            User::where('email', $request->contact_person_email)->exists() => [
                'status_code' => 403,
                'message'     => translate('messages.email_already_taken'),
                'code'        => 'contact_person_email',
            ],
            default => null,
        };

        if ($validationError) {
            return $validationError;
        }

        $user = new User();
        $user->f_name = $request->contact_person_name;
        $user->email = $request->contact_person_email;
        $user->phone = $request->contact_person_number;
        $user->password = bcrypt($request->password);
        $user->ref_code = Helpers::generate_referer_code($user);
        $user->login_medium = 'manual';
        $user->save();

        try {
            if (config('mail.status') && $request->contact_person_email && Helpers::get_mail_status('registration_mail_status_user') == '1' && Helpers::getNotificationStatusData('customer', 'customer_registration', 'mail_status')) {
                Mail::to($request->contact_person_email)->send(new CustomerRegistration($request->contact_person_name));
            }
        } catch (\Exception $exception) {
            info([$exception->getFile(), $exception->getLine(), $exception->getMessage()]);
        }
        if ($request->guest_id  && isset($user->id)) {

            $userStoreIds = Cart::where('user_id', $request->guest_id)
                ->join('items', 'carts.item_id', '=', 'items.id')
                ->pluck('items.store_id')
                ->toArray();

            Cart::where('user_id', $user->id)
                ->whereHas('item', function ($query) use ($userStoreIds) {
                    $query->whereNotIn('store_id', $userStoreIds);
                })
                ->delete();

            Cart::where('user_id', $request->guest_id)->update(['user_id' => $user->id, 'is_guest' => 0]);
        }

        return ['newUser' => true, 'user' => $user];
    }


    private function validationCheck($request)
    {
        $validationError = match (true) {
            $request->is_guest && !Helpers::get_mail_status('guest_checkout_status') => [
                'code'    => 'is_guest',
                'message' => translate('messages.Guest_order_is_not_active'),
                'status_code' => 403,
            ],

            $request->order_type === 'delivery' && !Helpers::get_business_settings('home_delivery_status') => [
                'code'    => 'order_type',
                'message' => translate('messages.home_delivery_is_not_active'),
                'status_code' => 403,
            ],

            $request->order_type === 'take_away' && !Helpers::get_business_settings('takeaway_status') => [
                'code'    => 'order_type',
                'message' => translate('messages.take_away_is_not_active'),
                'status_code' => 403,
            ],

            $request->partial_payment && !Helpers::get_business_settings('partial_payment_status') => [
                'code'    => 'order_method',
                'message' => translate('messages.partial_payment_is_not_active'),
                'status_code' => 403,
            ],

            $request->payment_method === 'offline_payment' && !Helpers::get_mail_status('offline_payment_status') => [
                'code'    => 'offline_payment_status',
                'message' => translate('messages.offline_payment_for_the_order_not_available_at_this_time'),
                'status_code' => 403,
            ],

            $request->payment_method === 'digital_payment' && !Helpers::get_business_settings('digital_payment')['status'] => [
                'code'    => 'digital_payment',
                'message' => translate('messages.digital_payment_for_the_order_not_available_at_this_time'),
                'status_code' => 403,
            ],

            default => null,
        };

        if ($validationError) {
            return $validationError;
        }

        return null;
    }

    private function getVehicleExtraCharge($distance)
    {
        $data =  DMVehicle::active()->where(function ($query) use ($distance) {
            $query->where('starting_coverage_area', '<=', $distance)->where('maximum_coverage_area', '>=', $distance)
                ->orWhere(function ($query) use ($distance) {
                    $query->where('starting_coverage_area', '>=', $distance);
                });
        })
            ->orderBy('starting_coverage_area')->first();
        return ['extraCharge' => (float) (isset($data) ? $data->extra_charges  : 0), 'vehicle_id' => $data?->id];
    }
    private function getZoneAndStore($request, $schedule_at)
    {
        if ($request->latitude && $request->longitude) {
            if ($request->order_type == 'parcel') {
                $zone_ids = $request->header('zoneId') ? json_decode($request->header('zoneId'), true) : [];
                $zone = Zone::whereIn('id', $zone_ids)->whereContains('coordinates', new Point($request->latitude, $request->longitude, POINT_SRID))->wherehas('modules', function ($q) {
                    $q->where('module_type', 'parcel');
                })->first();


                $receiver_zone_id =  json_decode($request->receiver_details, true)['zone_id'];
                $receiverZone = Zone::where('id', $receiver_zone_id)->whereContains('coordinates', new Point(json_decode($request->receiver_details, true)['latitude'], json_decode($request->receiver_details, true)['longitude'], POINT_SRID))->first();
                if (!$receiverZone) {
                    return [
                        'status_code' => 403,
                        'code' => 'receiverZone',
                        'message' => translate('messages.out_of_coverage'),
                    ];
                }
            } else {
                $store = Store::with(['discount', 'store_sub'])->selectRaw('*, IF(((select count(*) from `store_schedule` where `stores`.`id` = `store_schedule`.`store_id` and `store_schedule`.`day` = ' . $schedule_at->format('w') . ' and `store_schedule`.`opening_time` < "' . $schedule_at->format('H:i:s') . '" and `store_schedule`.`closing_time` >"' . $schedule_at->format('H:i:s') . '") > 0), true, false) as open')->where('id', $request->store_id)->first();
                if ($store) {
                    $zone = Zone::where('id', $store->zone_id)->whereContains('coordinates', new Point($request->latitude, $request->longitude, POINT_SRID))->first();
                }
            }
        }
        return ['zone' => $zone ?? null, 'store' => $store ?? null];
    }


    private function zoneAndStoreValidationCheck($request, $schedule_at, $zone, $store)
    {
        $store_sub = $store?->store_sub;
        $validationError = match (true) {
            !$zone => [
                'code'    => 'zone',
                'message' => translate('messages.out_of_coverage_area'),
                'status_code' => 403,
            ],
            default => null,
        };

        if ($request->order_type !== 'parcel') {
            $validationError = match (true) {
                !$store => [
                    'code'    => 'store',
                    'message' => translate('messages.store_not_found'),
                    'status_code' => 403,
                ],
                $request->schedule_at && $schedule_at < now() => [
                    'code'    => 'order_time',
                    'message' => translate('messages.you_can_not_schedule_a_order_in_past'),
                    'status_code' => 403,
                ],
                $request->schedule_at && !$store->schedule_order => [
                    'code'    => 'schedule_at',
                    'message' => translate('messages.schedule_order_not_available'),
                    'status_code' => 403,
                ],
                $store->open == false => [
                    'code'    => 'order_time',
                    'message' => translate('messages.store_is_closed_at_order_time'),
                    'status_code' => 403,
                ],
                $store->store_business_model == 'unsubscribed' => [
                    'code'    => 'order-confirmation-model',
                    'message' => translate('messages.Sorry_the_store_is_unable_to_take_any_order_!'),
                    'status_code' => 403,
                ],

                $store->is_valid_subscription && $store_sub && $store_sub->max_order != "unlimited" && $store_sub->max_order <= 0 => [
                    'code'    => 'order-confirmation-error',
                    'message' => translate('messages.Sorry_the_store_is_unable_to_take_any_order_!'),
                    'status_code' => 403,
                ],
                default => null,
            };
        }

        if ($validationError) {
            return $validationError;
        }

        return null;
    }
    private function getCouponData($request)
    {

        if ($request['coupon_code']) {
            $coupon = Coupon::active()->where(['code' => $request['coupon_code']])->first();

            if (!$coupon) {
                return [
                    'status_code' => 403,
                    'code' => 'coupon',
                    'message' => translate('messages.coupon_expire'),
                ];
            }

            $status = $request->is_guest
                ? CouponLogic::is_valid_for_guest($coupon, $request['store_id'])
                : CouponLogic::is_valide($coupon, $request->user->id, $request['store_id']);

            $validationError = match ($status) {
                407 => [
                    'status_code' => 403,
                    'code' => 'coupon',
                    'message' => translate('messages.coupon_expire'),
                ],
                408 => [
                    'status_code' => 403,
                    'code' => 'coupon',
                    'message' => translate('messages.You_are_not_eligible_for_this_coupon'),
                ],
                406 => [
                    'status_code' => 403,
                    'code' => 'coupon',
                    'message' => translate('messages.coupon_usage_limit_over'),
                ],
                404 => [
                    'status_code' => 403,
                    'code' => 'coupon',
                    'message' => translate('messages.not_found'),
                ],
                default => null,
            };

            if ($validationError) {
                return $validationError;
            }

            $coupon_created_by = $coupon->created_by;

            if ($coupon->coupon_type === 'free_delivery') {
                $delivery_charge = 0;
                $free_delivery_by = $coupon_created_by;
                $coupon_created_by = null;
            }
        }

        return [
            'coupon' => $coupon ?? null,
            'coupon_created_by' => $coupon_created_by ?? null,
            'delivery_charge' => $delivery_charge ?? null,
            'free_delivery_by' => $free_delivery_by ?? null,
        ];
    }

    private function getDeliveryCharge($request, $zone, $store, $module_wise_delivery_charge, $delivery_charge)
    {
        if ($zone?->increased_delivery_fee_status == 1) {
            $increased = $zone->increased_delivery_fee ?? 0;
        }
        $vehicleExtraCharge = $this->getVehicleExtraCharge($request->distance ?? 0);
        $extra_charges = $vehicleExtraCharge['extraCharge'];
        $vehicle_id = $vehicleExtraCharge['vehicle_id'];

        if ($request->order_type !== 'parcel') {

            if ($request['order_type'] === 'take_away') {
                return [
                    'vehicle_id' => null,
                    'original_delivery_charge' => 0,
                    'delivery_charge' => 0,
                ];
            }

            if ($store?->sub_self_delivery == 1) {
                $per_km_shipping_charge = $store->per_km_shipping_charge;
                $minimum_shipping_charge = $store->minimum_shipping_charge;
                $maximum_shipping_charge = $store->maximum_shipping_charge;
                $extra_charges = 0;
                $vehicle_id = null;
                $increased = 0;
            } elseif ($module_wise_delivery_charge) {
                $per_km_shipping_charge = $module_wise_delivery_charge->pivot->per_km_shipping_charge;
                $minimum_shipping_charge = $module_wise_delivery_charge->pivot->minimum_shipping_charge;
                $maximum_shipping_charge = $module_wise_delivery_charge->pivot->maximum_shipping_charge;
            } else {
                $per_km_shipping_charge = 0;
                $minimum_shipping_charge = 0;
                $maximum_shipping_charge = 0;
            }

            $original_delivery_charge = (($request->distance * $per_km_shipping_charge) > $minimum_shipping_charge) ? $request->distance * $per_km_shipping_charge  : $minimum_shipping_charge;


            if ($maximum_shipping_charge  >= $minimum_shipping_charge  && $original_delivery_charge >  $maximum_shipping_charge) {
                $original_delivery_charge = $maximum_shipping_charge;
            } else {
                $original_delivery_charge = $original_delivery_charge;
            }

            if (!isset($delivery_charge)) {
                $delivery_charge = ($request->distance * $per_km_shipping_charge > $minimum_shipping_charge) ? $request->distance * $per_km_shipping_charge : $minimum_shipping_charge;
                if ($maximum_shipping_charge  >= $minimum_shipping_charge  && $delivery_charge >  $maximum_shipping_charge) {
                    $delivery_charge = $maximum_shipping_charge;
                } else {
                    $delivery_charge = $delivery_charge;
                }
            }
            $original_delivery_charge = $original_delivery_charge + $extra_charges;
            $delivery_charge = $delivery_charge + $extra_charges;
        } else {
            $parcel_category = ParcelCategory::first($request->parcel_category_id);
            if ($parcel_category?->parcel_minimum_shipping_charge) {
                $per_km_shipping_charge = $parcel_category->parcel_per_km_shipping_charge;
                $minimum_shipping_charge = $parcel_category->parcel_minimum_shipping_charge;
            } else {
                $businessSetting = BusinessSetting::whereIn('key', [
                    'parcel_per_km_shipping_charge',
                    'parcel_minimum_shipping_charge',
                ])->pluck('value', 'key');

                $per_km_shipping_charge = (float) ($businessSetting['parcel_per_km_shipping_charge'] ?? 0);
                $minimum_shipping_charge = (float) ($businessSetting['parcel_minimum_shipping_charge'] ?? 0);
            }

            $original_delivery_charge = (($request->distance * $per_km_shipping_charge) > $minimum_shipping_charge) ? ($request->distance * $per_km_shipping_charge) + $extra_charges : ($minimum_shipping_charge + $extra_charges);
        }

        if ($increased > 0) {
            if ($delivery_charge > 0) {
                $increased_fee = ($delivery_charge * $increased) / 100;
                $delivery_charge = $delivery_charge + $increased_fee;
            }
            if ($original_delivery_charge > 0) {
                $increased_fee = ($original_delivery_charge * $increased) / 100;
                $original_delivery_charge = $original_delivery_charge + $increased_fee;
            }
        }
        return [
            'delivery_charge' => $delivery_charge,
            'original_delivery_charge' => $original_delivery_charge ?? 0,
            'vehicle_id' => $vehicle_id ?? null,
        ];
    }

    private function sentOrderPlaceNotification($request, $order, $store)
    {
        $payments = $order->payments()->where('payment_method', 'cash_on_delivery')->exists();
        try {
            if (!in_array($order->payment_method, ['digital_payment', 'partial_payment', 'offline_payment'])  || $payments) {
                if ($store?->is_valid_subscription == 1 && $store?->store_sub?->max_order != "unlimited" && $store?->store_sub?->max_order > 0) {
                    $store?->store_sub?->decrement('max_order', 1);
                }
                Helpers::send_order_notification($order);

                $email = $order->is_guest == 1 ? $request->contact_person_email : $request->user?->email;
                $name = $order->is_guest == 1 ? $request->contact_person_name : $request->user?->f_name;
                if (config('mail.status') && $email && $order->order_status == 'pending') {
                    if ($order->order_status == 'pending'  &&  Helpers::get_mail_status('place_order_mail_status_user') == '1' && Helpers::getNotificationStatusData('customer', 'customer_order_notification', 'mail_status')) {
                        Mail::to($email)->send(new PlaceOrder($order->id));
                    }
                    if (config('order_delivery_verification') == 1 && Helpers::get_mail_status('order_verification_mail_status_user') == '1'  && Helpers::getNotificationStatusData('customer', 'customer_delivery_verification', 'mail_status')) {
                        Mail::to($email)->send(new OrderVerificationMail($order->otp, $name));
                    }
                }
            }
        } catch (\Exception $exception) {
            info([$exception->getFile(), $exception->getLine(), $exception->getMessage()]);
        }
        return true;
    }

    private function makeOrderDetails($carts, $request, $order, $store)
    {
        $total_addon_price = 0;
        $product_price = 0;
        $store_discount_amount = 0;
        $flash_sale_vendor_discount_amount = 0;
        $flash_sale_admin_discount_amount = 0;
        $product_data = [];
        $order_details = [];
        $variations = [];

        foreach ($carts as $c) {
            $isCampaign = false;
            if ($c['item_type'] === 'App\Models\ItemCampaign' || $c['item_type'] === 'AppModelsItemCampaign') {
                $product = ItemCampaign::with('module')->active()->find($c['item_id']);
                $isCampaign = true;
            } else {
                $product = Item::with('module')->active()->find($c['item_id']);
            }

            if ($product) {
                if ($product->store_id != $order->store_id) {
                    return [
                        'status_code' => 403,
                        'code' => 'different_stores',
                        'message' => translate('messages.Please_select_items_from_the_same_store'),
                    ];
                }

                if ($product?->pharmacy_item_details?->is_prescription_required == '1' && empty($request->file('order_attachment'))) {
                    return [
                        'status_code' => 403,
                        'code' => 'prescription',
                        'message' => translate('messages.prescription_is_required_for_this_order'),
                    ];
                }

                if ($product?->maximum_cart_quantity && $c['quantity'] > $product?->maximum_cart_quantity) {
                    return [
                        'status_code' => 403,
                        'code' => 'quantity',
                        'message' => translate('messages.maximum_cart_quantity_limit_over'),
                    ];
                }


                $foodVariation = false;
                if ($product?->module?->module_type == 'food') {
                    $foodVariation = true;
                    $product_variations = json_decode($product->food_variations, true);

                    if (count($product_variations)) {
                        $variation_data = Helpers::get_varient($product_variations, $c['variation']);
                        $price = $product['price'] + $variation_data['price'];
                        $variations = $variation_data['variations'];
                    } else {
                        $price = $product['price'];
                    }
                } else {
                    if (count(json_decode($product['variations'], true)) > 0 && count($c['variation']) > 0) {
                        $variant_data = Helpers::variation_price($product, json_encode($c['variation']));
                        $price = $variant_data['price'];
                        $stock = $variant_data['stock'];
                    } else {
                        $price = $product['price'];
                        $stock = $product?->stock;
                    }

                    if (config('module.' . $product->module->module_type)['stock']) {
                        if ($c['quantity'] > $stock) {

                            return [
                                'status_code' => 403,
                                'code' => 'stock',
                                'message' => $product->title . ' ' . translate('messages.is_out_of_stock')
                            ];
                        }
                        $product_data[] = [
                            'item' => clone $product,
                            'quantity' => $c['quantity'],
                            'variant' => count($c['variation']) > 0 ? $c['variation'][0]['type'] : null
                        ];
                    }
                }



                $product = Helpers::product_data_formatting($product, false, false, app()->getLocale());
                $addon_data = Helpers::calculate_addon_price(AddOn::whereIn('id', $c['add_on_ids'])->get(), $c['add_on_qtys']);
                $product_discount = Helpers::product_discount_calculate($product, $price, $store);

                $or_d = [
                    'item_id' => $isCampaign ?  null : $c['item_id'],
                    'item_campaign_id' => $isCampaign ? $c['item_id'] : null,
                    'item_details' => json_encode($product),
                    'quantity' => $c['quantity'],
                    'price' => round($price, config('round_up_to_digit')),
                    'tax_amount' => round(Helpers::tax_calculate($product, $price), config('round_up_to_digit')),
                    'discount_on_item' => $product_discount['discount_amount'],
                    'discount_type' => $product_discount['discount_type'],
                    'variant' => json_encode($c['variant']),
                    'variation' => $foodVariation ? json_encode($variations) : json_encode($c['variation']),
                    'add_ons' => json_encode($addon_data['addons']),
                    'total_add_on_price' => round($addon_data['total_add_on_price'], config('round_up_to_digit')),
                    'created_at' => now(),
                    'updated_at' => now()
                ];


                $total_addon_price += $or_d['total_add_on_price'];
                $product_price += $price * $or_d['quantity'];
                $store_discount_amount += $or_d['discount_type'] != 'flash_sale' ? $or_d['discount_on_item'] * $or_d['quantity'] : 0;
                $flash_sale_admin_discount_amount += $or_d['discount_type'] == 'flash_sale' ? $product_discount['admin_discount_amount'] * $or_d['quantity'] : 0;
                $flash_sale_vendor_discount_amount += $or_d['discount_type'] == 'flash_sale' ? $product_discount['vendor_discount_amount'] * $or_d['quantity'] : 0;
                $order_details[] = $or_d;
            } else {
                return [
                    'status_code' => 403,
                    'code' => 'not_found',
                    'message' => translate('messages.product_not_found'),
                ];
            }
        }
        return [
            'order_details' => $order_details,
            'total_addon_price' => $total_addon_price,
            'product_price' => $product_price,
            'store_discount_amount' => $store_discount_amount,
            'flash_sale_admin_discount_amount' => $flash_sale_admin_discount_amount,
            'flash_sale_vendor_discount_amount' => $flash_sale_vendor_discount_amount,
            'product_data' => $product_data

        ];
    }
}

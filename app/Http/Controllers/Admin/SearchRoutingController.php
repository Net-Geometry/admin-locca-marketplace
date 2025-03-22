<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AddOn;
use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\Advertisement;
use App\Models\Banner;
use App\Models\Campaign;
use App\Models\CashBack;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Coupon;
use App\Models\DeliveryMan;
use App\Models\Disbursement;
use App\Models\DMVehicle;
use App\Models\Item;
use App\Models\ItemCampaign;
use App\Models\Notification;
use App\Models\Order;
use App\Models\RecentSearch;
use App\Models\Store;
use App\Models\StoreSubscription;
use App\Models\SubscriptionPackage;
use App\Models\User;
use App\Models\WalletBonus;
use App\Models\WithdrawalMethod;
use App\Models\WithdrawRequest;
use App\Models\Zone;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;


class SearchRoutingController extends Controller
{
    public function index(Request $request)
    {
        $searchKeyword = $request->input('search');
        session(['search_keyword' => $searchKeyword]);

        //1st layer
        $formattedRoutes = [];
        $jsonFilePath = public_path('admin_formatted_routes.json');
        if (file_exists($jsonFilePath)) {
            $fileContents = file_get_contents($jsonFilePath);
            $routes = json_decode($fileContents, true);

            foreach ($routes as $route) {
                $uri = $route['URI'];

                if (Str::contains(strtolower($route['keywords']), strtolower($searchKeyword))) {
                    $hasParameters = preg_match('/\{(.*?)\}/', $uri);
                    $fullURL = $this->routeFullUrl($uri);

                    if (!$hasParameters) {
                        $routeName = $route['routeName'];
                        $formattedRoutes[] = [
                            'routeName' => ucwords($routeName),
                            'URI' => $uri,
                            'fullRoute' => $fullURL,
                        ];
                    }
                }
            }
        }

        //2nd layer
        $routes = Route::getRoutes();
        $adminRoutes = collect($routes->getRoutesByMethod()['GET'])->filter(function ($route) {
            return str_starts_with($route->uri(), 'admin');
        });
        $validRoutes = [];
        if (is_numeric($searchKeyword) && $searchKeyword > 0) {
            //store
            $store = Store::with('vendor')
                ->where('id', $searchKeyword)
                ->orWhereHas('vendor', function ($query) use ($searchKeyword){
                    $query->where('id', $searchKeyword);
                })
                ->first();

            if ($store){
                if ($store->status == 1){
                    $storeRoutes = $adminRoutes->filter(function ($route) {
                        return str_contains($route->uri(), 'store') &&
                            (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'view'))
                            && !str_contains($route->uri(), 'withdraw-view');
                    });
                }else{
                    $storeRoutes = $adminRoutes->filter(function ($route) {
                        return str_contains($route->uri(), 'store') &&
                            (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'view'))
                            && !str_contains($route->uri(), 'withdraw-view');

                    });
                }

                if (isset($storeRoutes)) {
                    foreach ($storeRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $store, route: $route, type: 'store', prefix: 'Store');
                    }
                }
            }

            //order
            $order = Order::find($searchKeyword);
            if ($order){
                $orderRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'order') && str_contains($route->uri(), 'details');
                });
                if (isset($orderRoutes)) {
                    foreach ($orderRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $order, route: $route, type: 'order', prefix: 'Order');
                    }
                }
            }

            //multiple orders with customer id
            $orders = Order::with(['customer', 'store'])
                ->whereHas('customer', function ($query) use ($searchKeyword){
                    $query->where('id', $searchKeyword);
                })
                ->orWhereHas('store', function ($query) use ($searchKeyword){
                    $query->where('id', $searchKeyword);
                })
                ->get();

            if ($orders){
                $ordersRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'order') && str_contains($route->uri(), 'details')
                        && !str_contains($route->uri(), 'post');
                });
                if (isset($ordersRoutes)) {
                    foreach ($orders as $order)
                    {
                        foreach ($ordersRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $order, route: $route, type: 'order', prefix: 'Order');
                        }
                    }
                }
            }

            //zone
            $zone = Zone::find($searchKeyword);
            if ($zone){
                $zoneRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'zone') &&
                        (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'settings'));
                });

                if (isset($zoneRoutes)) {
                    foreach ($zoneRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $zone, route: $route, type: 'zone', prefix: 'Zone');
                    }
                }
            }

            //category
            $category = Category::find($searchKeyword);
            if ($category){
                $categoryRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'category') && str_contains($route->uri(), 'edit');
                });

                if (isset($categoryRoutes)) {
                    foreach ($categoryRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $category, route: $route, type: 'category', prefix: 'Category');
                    }
                }
            }

            //cuisine
//            $cuisine = Cuisine::find($searchKeyword);
//            if ($cuisine){
//                $cuisineRoutes = $adminRoutes->filter(function ($route) {
//                    return str_contains($route->uri(), 'cuisine') && str_contains($route->uri(), 'add');
//                });
//
//                if (isset($cuisineRoutes)) {
//                    foreach ($cuisineRoutes as $route) {
//                        $validRoutes[] = $this->filterRoute(model: $cuisine, route: $route, prefix: 'Cuisine');
//                    }
//                }
//            }

            //AddOn
            $addOn = AddOn::find($searchKeyword);
            if ($addOn){
                $addOnRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'addon') && str_contains($route->uri(), 'edit');
                });

                if (isset($addOnRoutes)) {
                    foreach ($addOnRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $addOn, route: $route, prefix: 'Addon');
                    }
                }
            }

            //Item
            $Item = Item::find($searchKeyword);
            if ($Item){
                $ItemRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'Item')
                        && (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'view'))
                        && !str_contains($route->uri(), 'status') && !str_contains($route->uri(), 'Item-wise')
                        && !str_contains($route->uri(), 'reviews-export');
                });

                if (isset($ItemRoutes)) {
                    foreach ($ItemRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $Item, route: $route, type: 'Item', prefix: 'Item');
                    }
                }
            }

            //basic campaign
            $campaign = Campaign::find($searchKeyword);
            if ($campaign){
                $campaignRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'campaign')
                        && (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'view'));
                });

                if (isset($campaignRoutes)) {
                    foreach ($campaignRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $campaign, route: $route, type: 'basic-campaign', prefix: 'Basic Campaign');
                    }
                }
            }

            //item campaign
            $itemCampaign = ItemCampaign::find($searchKeyword);
            if ($itemCampaign){
                $itemCampaignRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'campaign')
                        && (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'view'));
                });

                if (isset($itemCampaignRoutes)) {
                    foreach ($itemCampaignRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $itemCampaign, route: $route, type: 'item-campaign', prefix: 'Item Campaign');
                    }
                }
            }

            //coupon
            $coupon = Coupon::find($searchKeyword);
            if ($coupon){
                $couponRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'coupon') && str_contains($route->uri(), 'update');
                });

                if (isset($couponRoutes)) {
                    foreach ($couponRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $coupon, route: $route, prefix: 'Coupon');
                    }
                }
            }

            //cashback
            $cashback = CashBack::find($searchKeyword);
            if ($cashback){
                $cashbackRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'cashback') && str_contains($route->uri(), 'edit');
                });

                if (isset($cashbackRoutes)) {
                    foreach ($cashbackRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $cashback, route: $route, prefix: 'Cashback');
                    }
                }
            }

            //banner
            $banner = Banner::find($searchKeyword);
            if ($banner){
                $bannerRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'banner') && str_contains($route->uri(), 'edit')
                        && !str_contains($route->uri(), 'promotional-banner');
                });

                if (isset($bannerRoutes)) {
                    foreach ($bannerRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $banner, route: $route, prefix: 'Banner');
                    }
                }
            }

            //Advertisement
            $ads = Advertisement::find($searchKeyword);
            if ($ads){
                $adsRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'advertisement')
                        && (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'detail'));
                });
                if (isset($adsRoutes)) {
                    foreach ($adsRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $ads, route: $route, prefix: 'Advertisement');
                    }
                }
            }

            // $contact = ContactMessage::find($searchKeyword);
            // if ($contact){
            //     $contactRoutes = $adminRoutes->filter(function ($route) {
            //         return str_contains($route->uri(), 'contact') && str_contains($route->uri(), 'view');
            //     });

            //     if (isset($contactRoutes)) {
            //         foreach ($contactRoutes as $route) {
            //             $validRoutes[] = $this->filterRoute(model: $contact, route: $route, prefix: 'Contact');
            //         }
            //     }
            // }

            $notification = Notification::find($searchKeyword);
            if ($notification){
                $notificationRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'notification') && str_contains($route->uri(), 'edit');
                });

                if (isset($notificationRoutes)) {
                    foreach ($notificationRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $notification, route: $route, prefix: 'Notification');
                    }
                }
            }

            //customer
            $customer = User::find($searchKeyword);
            if ($customer){
                $customerRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'customer') &&  str_contains($route->uri(), 'view');
                });

                if (isset($customerRoutes)) {
                    foreach ($customerRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $customer, route: $route, type: 'customer', prefix: 'Customer');
                    }
                }
            }

            //bonus
            $bonus = WalletBonus::find($searchKeyword);
            if ($bonus){
                $bonusRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'bonus') &&  str_contains($route->uri(), 'update');
                });

                if (isset($bonusRoutes)) {
                    foreach ($bonusRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $bonus, route: $route, type: 'bonus', prefix: 'Bonus');
                    }
                }
            }

            //bonus
            $vehicle = DMVehicle::find($searchKeyword);
            if ($vehicle){
                $vehicleRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'vehicle') &&
                        str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'view')
                        && !str_contains($route->uri(), 'review') && !str_contains($route->uri(), 'Item')
                        && !str_contains($route->uri(), 'campaign') && !str_contains($route->uri(), 'store')
                        && !str_contains($route->uri(), 'order') && !str_contains($route->uri(), 'message')
                        && !str_contains($route->uri(), 'delivery-man') && !str_contains($route->uri(), 'pos')
                        && !str_contains($route->uri(), 'customer') && !str_contains($route->uri(), 'subscription')
                        && !str_contains($route->uri(), 'social-login') && !str_contains($route->uri(), 'contact')
                        ;
                });

                if (isset($vehicleRoutes)) {
                    foreach ($vehicleRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $vehicle, route: $route, type: 'vehicle', prefix: 'Vehicle');
                    }
                }
            }

            //delivery man
            $deliveryMan = DeliveryMan::find($searchKeyword);
            if ($deliveryMan){
                $deliveryManRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'delivery-man')
                        && str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'preview');
                });
                if (isset($deliveryManRoutes)) {
                    foreach ($deliveryManRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $deliveryMan, route: $route, type: 'deliveryMan', prefix: 'Delivery Man');
                    }
                }
            }

            //store disbursement
            $storeDisbursement = Disbursement::where('created_for', 'store')->find($searchKeyword);
            if ($storeDisbursement){
                $storeDisbursementRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'store-disbursement') &&  str_contains($route->uri(), 'details');
                });

                if (isset($storeDisbursementRoutes)) {
                    foreach ($storeDisbursementRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $storeDisbursement, route: $route, prefix: 'Store Disbursement');
                    }
                }
            }

            //delivery man disbursement
            $dmDisbursement = Disbursement::where('created_for', 'delivery_man')->find($searchKeyword);
            if ($dmDisbursement){
                $dmDisbursementRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'dm-disbursement') &&  str_contains($route->uri(), 'details');
                });

                if (isset($dmDisbursementRoutes)) {
                    foreach ($dmDisbursementRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $dmDisbursement, route: $route, prefix: 'Delivery Man Disbursement');
                    }
                }
            }

            //withdraw requests
            $withdrawRequest = WithdrawRequest::find($searchKeyword);
            if ($withdrawRequest){
                $withdrawRequestRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'withdraw') && str_contains($route->uri(), 'withdraw-view');
                });

                if (isset($withdrawRequestRoutes)) {
                    foreach ($withdrawRequestRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $withdrawRequest, route: $route, prefix: 'Withdraw Request');
                    }
                }
            }

            //withdraw method
            $withdrawalMethod = WithdrawalMethod::find($searchKeyword);
            if ($withdrawalMethod){
                $withdrawalMethodRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'withdraw-method') && str_contains($route->uri(), 'edit');
                });

                if (isset($withdrawalMethodRoutes)) {
                    foreach ($withdrawalMethodRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $withdrawalMethod, route: $route, prefix: 'Withdraw Method');
                    }
                }
            }

            //Employee role
            $adminRole = AdminRole::find($searchKeyword);
            if ($adminRole){
                $adminRoleRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'custom-role') && str_contains($route->uri(), 'edit');
                });

                if (isset($adminRoleRoutes)) {
                    foreach ($adminRoleRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $adminRole, route: $route, prefix: 'Employee Role');
                    }
                }
            }

            //Employee role
            $employee = Admin::whereNotIn('id', [1])->find($searchKeyword);
            if ($employee){
                $employeeRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'employee') && str_contains($route->uri(), 'update');
                });

                if (isset($employeeRoutes)) {
                    foreach ($employeeRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $employee, route: $route, prefix: 'Employee');
                    }
                }
            }

            //subscription package
            $subscriptionPackage = SubscriptionPackage::find($searchKeyword);
            if ($subscriptionPackage){
                $subscriptionPackageRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'package')
                        && (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'details'));
                });

                if (isset($subscriptionPackageRoutes)) {
                    foreach ($subscriptionPackageRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $subscriptionPackage, route: $route, prefix: 'Subscription');
                    }
                }
            }

            //subscriber
            $storeSubscription = StoreSubscription::find($searchKeyword);
            if ($storeSubscription){
                $storeSubscriptionRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'subscription') && str_contains($route->uri(), 'subscriber-detail');
                });

                if (isset($storeSubscriptionRoutes)) {
                    foreach ($storeSubscriptionRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $storeSubscription, route: $route, prefix: 'Subscription');
                    }
                }
            }

        }
        else {
            //Store
            $stores = Store::where('name', 'LIKE', '%' . $searchKeyword . '%')
                ->orWhere('phone', 'LIKE', '%' . $searchKeyword . '%')
                ->orWhere('email', 'LIKE', '%' . $searchKeyword . '%')
                ->orWhere('address', 'LIKE', '%' . $searchKeyword . '%')
                ->orWhere('meta_title', 'LIKE', '%' . $searchKeyword . '%')
                ->orWhere('meta_description', 'LIKE', '%' . $searchKeyword . '%')
                ->orWhereHas('vendor', function($query) use ($searchKeyword){
                    return $query->where('f_name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('l_name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('email', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhereRaw("CONCAT(f_name, ' ', l_name) LIKE ?", ['%' . $searchKeyword . '%'])
                        ->orWhereRaw("CONCAT(l_name, ' ', f_name) LIKE ?", ['%' . $searchKeyword . '%'])
                        ->orWhereRaw("CONCAT(l_name,f_name) LIKE ?", ['%' . $searchKeyword . '%'])
                        ->orWhereRaw("CONCAT(f_name,l_name) LIKE ?", ['%' . $searchKeyword . '%']);
                })
                ->get();

            if ($stores){
                foreach ($stores as $store){
                    if ($store->status == 1){
                        $storeRoutes = $adminRoutes->filter(function ($route) {
                            return str_contains($route->uri(), 'store') &&
                                (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'view'))
                                && !str_contains($route->uri(), 'withdraw-view');
                        });
                    }else{
                        $storeRoutes = $adminRoutes->filter(function ($route) {
                            return str_contains($route->uri(), 'store') &&
                                (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'view'))
                                && !str_contains($route->uri(), 'withdraw-view');

                        });
                    }

                    if (isset($storeRoutes)) {
                        foreach ($storeRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $store, route: $route, type: 'store', prefix: 'Store');
                        }
                    }
                }
            }

            //Order
            $orders = Order::with('customer')
                ->whereHas('customer', function ($query) use ($searchKeyword){
                    $query->where('f_name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('l_name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('email', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhereRaw("CONCAT(f_name, ' ', l_name) LIKE ?", ['%' . $searchKeyword . '%'])
                        ->orWhereRaw("CONCAT(f_name,l_name) LIKE ?", ['%' . $searchKeyword . '%'])
                        ->orWhereRaw("CONCAT(l_name,f_name) LIKE ?", ['%' . $searchKeyword . '%']);
                })
                ->orWhereHas('store', function ($query) use ($searchKeyword){
                    $query->where('name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('email', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('address', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('meta_title', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('meta_description', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($orders){
                $ordersRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'order') && str_contains($route->uri(), 'details')
                        && !str_contains($route->uri(), 'post');
                });
                if (isset($ordersRoutes)) {
                    foreach ($orders as $order)
                    {
                        foreach ($ordersRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $order, route: $route, type: 'order', prefix: 'Order');
                        }
                    }
                }
            }

            //Zone
            $zones = Zone::
                where(function($query) use ($searchKeyword) {
                    $query->where('name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('display_name', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($zones){
                $zoneRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'zone') &&
                        (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'settings'));
                });

                if (isset($zoneRoutes)) {
                    foreach ($zones as $zone) {
                        foreach ($zoneRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $zone, route: $route, type: 'zone', prefix: 'Zone');
                        }
                    }
                }
            }

            //Category
            $categories = Category::
                where(function($query) use ($searchKeyword) {
                    $query->where('name', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($categories){
                $categoryRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'category') && str_contains($route->uri(), 'edit');
                });

                if (isset($categoryRoutes)) {
                    foreach ($categories as $category) {
                        foreach ($categoryRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $category, route: $route, type: 'category', prefix: 'Category');
                        }
                    }
                }
            }

            //AddOn
            $addOns = AddOn::
                where(function($query) use ($searchKeyword) {
                    $query->where('name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('stock_type', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($addOns){
                $addOnRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'addon') && str_contains($route->uri(), 'edit');
                });

                if (isset($addOnRoutes)) {
                    foreach ($addOns as $addOn) {
                        foreach ($addOnRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $addOn, route: $route, prefix: 'Addon');
                        }
                    }
                }
            }

            //Item
            $Items = Item::
                where(function($query) use ($searchKeyword) {
                    $query->where('name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('description', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('stock_type', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($Items){
                $ItemRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'Item')
                        && (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'view'))
                        && !str_contains($route->uri(), 'status') && !str_contains($route->uri(), 'Item-wise')
                        && !str_contains($route->uri(), 'reviews-export');
                });

                if (isset($ItemRoutes)) {
                    foreach ($Items as $Item) {
                        foreach ($ItemRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $Item, route: $route, type: 'Item', prefix: 'Item');
                        }
                    }
                }
            }

            //Campaign
            $campaigns = Campaign::
                where(function($query) use ($searchKeyword) {
                    $query->where('title', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('description', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($campaigns){
                $campaignRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'campaign')
                        && (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'view'));
                });

                if (isset($campaignRoutes)) {
                    foreach ($campaigns as $campaign) {
                        foreach ($campaignRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $campaign, route: $route, type: 'basic-campaign', prefix: 'Basic Campaign');
                        }
                    }
                }
            }

            //ItemCampaign
            $itemCampaigns = ItemCampaign::
                where(function($query) use ($searchKeyword) {
                    $query->where('title', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('description', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($itemCampaigns){
                $itemCampaignRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'campaign')
                        && (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'view'));
                });

                if (isset($itemCampaignRoutes)) {
                    foreach ($itemCampaigns as $itemCampaign) {
                        foreach ($itemCampaignRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $itemCampaign, route: $route, type: 'item-campaign', prefix: 'Item Campaign');
                        }
                    }
                }
            }

            //Coupon
            $coupons = Coupon::
                where(function($query) use ($searchKeyword) {
                    $query->where('title', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('code', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('discount_type', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('coupon_type', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($coupons){
                $couponRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'coupon') && str_contains($route->uri(), 'update');
                });

                if (isset($couponRoutes)) {
                    foreach ($coupons as $coupon) {
                        foreach ($couponRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $coupon, route: $route, prefix: 'Coupon');
                        }
                    }
                }
            }

            //CashBack
            $cashBacks = CashBack::
                where(function($query) use ($searchKeyword) {
                    $query->where('title', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('cashback_type', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($cashBacks){
                $cashbackRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'cashback') && str_contains($route->uri(), 'edit');
                });

                if (isset($cashbackRoutes)) {
                    foreach ($cashBacks as $cashBack) {
                        foreach ($cashbackRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $cashBack, route: $route, prefix: 'Cashback');
                        }
                    }
                }
            }

            //Banner
            $banners = Banner::
                where(function($query) use ($searchKeyword) {
                    $query->where('title', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('type', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($banners){
                $bannerRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'banner') && str_contains($route->uri(), 'edit')
                        && !str_contains($route->uri(), 'promotional-banner');
                });

                if (isset($bannerRoutes)) {
                    foreach ($banners as $banner) {
                        foreach ($bannerRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $banner, route: $route, prefix: 'Banner');
                        }
                    }
                }
            }

            //Advertisement
            $advertisements = Advertisement::
                where(function($query) use ($searchKeyword) {
                    $query->where('title', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('description', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('add_type', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($advertisements){
                $adsRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'advertisement')
                        && (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'detail'));
                });
                if (isset($adsRoutes)) {
                    foreach ($advertisements as $advertisement) {
                        foreach ($adsRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $advertisement, route: $route, prefix: 'Advertisement');
                        }
                    }
                }
            }

            //ContactMessage
            // $contactMessages = ContactMessage::
            //     where(function($query) use ($searchKeyword) {
            //         $query->where('name', 'LIKE', '%' . $searchKeyword . '%')
            //             ->orWhere('email', 'LIKE', '%' . $searchKeyword . '%')
            //             ->orWhere('mobile_number', 'LIKE', '%' . $searchKeyword . '%')
            //             ->orWhere('subject', 'LIKE', '%' . $searchKeyword . '%')
            //             ->orWhere('message', 'LIKE', '%' . $searchKeyword . '%')
            //             ->orWhere('reply', 'LIKE', '%' . $searchKeyword . '%');
            //     })
            //     ->get();

            // if ($contactMessages){
            //     $contactRoutes = $adminRoutes->filter(function ($route) {
            //         return str_contains($route->uri(), 'contact') && str_contains($route->uri(), 'view');
            //     });

            //     if (isset($contactRoutes)) {
            //         foreach ($contactMessages as $contactMessage) {
            //             foreach ($contactRoutes as $route) {
            //                 $validRoutes[] = $this->filterRoute(model: $contactMessage, route: $route, prefix: 'Contact');
            //             }
            //         }
            //     }
            // }

            //Notification
            $notifications = Notification::
                where(function($query) use ($searchKeyword) {
                    $query->where('title', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('description', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($notifications){
                $notificationRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'notification') && str_contains($route->uri(), 'edit');
                });

                if (isset($notificationRoutes)) {
                    foreach ($notifications as $notification) {
                        foreach ($notificationRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $notification, route: $route, prefix: 'Notification');
                        }
                    }
                }
            }

            //customer
            $customers = User::
                where(function($query) use ($searchKeyword) {
                    $query->where('f_name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('l_name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('email', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhereRaw("CONCAT(f_name, ' ', l_name) LIKE ?", ['%' . $searchKeyword . '%'])
                        ->orWhereRaw("CONCAT(f_name,l_name) LIKE ?", ['%' . $searchKeyword . '%'])
                        ->orWhereRaw("CONCAT(l_name,f_name) LIKE ?", ['%' . $searchKeyword . '%']);
                })
                ->get();

            if ($customers){
                $customerRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'customer') &&  str_contains($route->uri(), 'view');
                });
                if (isset($customerRoutes)) {
                    foreach ($customers as $customer){
                        foreach ($customerRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $customer, route: $route, type: 'customer', name: $customer->f_name. ' '. $customer->l_name, prefix: 'Customer');
                        }
                    }
                }
            }

            //WalletBonus
            $walletBonus = WalletBonus::
                where(function($query) use ($searchKeyword) {
                    $query->where('title', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('description', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('bonus_type', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($walletBonus){
                $bonusRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'bonus') &&  str_contains($route->uri(), 'update');
                });

                if (isset($bonusRoutes)) {
                    foreach ($walletBonus as $bonus) {
                        foreach ($bonusRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $bonus, route: $route, type: 'bonus', prefix: 'Bonus');
                        }
                    }
                }
            }

            //Vehicle
            $vehicles = DmVehicle::
                where(function($query) use ($searchKeyword) {
                    $query->where('type', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($vehicles){
                $vehicleRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'vehicle') &&
                        str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'view')
                        && !str_contains($route->uri(), 'review') && !str_contains($route->uri(), 'Item')
                        && !str_contains($route->uri(), 'campaign') && !str_contains($route->uri(), 'store')
                        && !str_contains($route->uri(), 'order') && !str_contains($route->uri(), 'message')
                        && !str_contains($route->uri(), 'delivery-man') && !str_contains($route->uri(), 'pos')
                        && !str_contains($route->uri(), 'customer') && !str_contains($route->uri(), 'subscription')
                        && !str_contains($route->uri(), 'social-login') && !str_contains($route->uri(), 'contact')
                        ;
                });

                if (isset($vehicleRoutes)) {
                    foreach ($vehicles as $vehicle) {
                        foreach ($vehicleRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $vehicle, route: $route, type: 'vehicle', prefix: 'Vehicle');
                        }
                    }
                }
            }

            //DeliveryMan
            $deliveryMen = DeliveryMan::
                where(function($query) use ($searchKeyword) {
                    $query->where('f_name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('l_name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('email', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('phone', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('identity_type', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhereRaw("CONCAT(f_name, ' ', l_name) LIKE ?", ['%' . $searchKeyword . '%'])
                        ->orWhereRaw("CONCAT(f_name,l_name) LIKE ?", ['%' . $searchKeyword . '%'])
                        ->orWhereRaw("CONCAT(l_name,f_name) LIKE ?", ['%' . $searchKeyword . '%']);
                })
                ->get();

            if ($deliveryMen){
                $deliveryManRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'delivery-man')
                        && str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'preview');
                });
                if (isset($deliveryManRoutes)) {
                    foreach ($deliveryMen as $deliveryMan) {
                        foreach ($deliveryManRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $deliveryMan, route: $route, type: 'deliveryMan', name: $deliveryMan->f_name. ' '. $deliveryMan->l_name, prefix: 'Delivery Man');
                        }
                    }
                }
            }

            //Store Disbursement
            $storeDisbursements = Disbursement::where('created_for', 'store')
                ->where(function($query) use ($searchKeyword) {
                    $query->where('title', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('description', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($storeDisbursements){
                $storeDisbursementRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'store-disbursement') &&  str_contains($route->uri(), 'details');
                });

                if (isset($storeDisbursementRoutes)) {
                    foreach ($storeDisbursements as $storeDisbursement)
                    foreach ($storeDisbursementRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $storeDisbursement, route: $route, prefix: 'Store Disbursement');
                    }
                }
            }

            //DM Disbursement
            $dmDisbursements = Disbursement::where('created_for', 'delivery_man')
                ->where(function($query) use ($searchKeyword) {
                    $query->where('title', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('description', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($dmDisbursements){
                $dmDisbursementRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'dm-disbursement') &&  str_contains($route->uri(), 'details');
                });

                if (isset($dmDisbursementRoutes)) {
                    foreach ($dmDisbursements as $dmDisbursement) {
                        foreach ($dmDisbursementRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $dmDisbursement, route: $route, prefix: 'Delivery Man Disbursement');
                        }
                    }
                }
            }

            //Withdraw Request
            $withdrawRequests = WithdrawRequest::
                where(function($query) use ($searchKeyword) {
                    $query->where('type', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('withdrawal_method_fields->account_name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('withdrawal_method_fields->account_number', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('withdrawal_method_fields->email', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($withdrawRequests){
                $withdrawRequestRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'withdraw') && str_contains($route->uri(), 'withdraw-view');
                });

                if (isset($withdrawRequestRoutes)) {
                    foreach ($withdrawRequests as $withdrawRequest) {
                        foreach ($withdrawRequestRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $withdrawRequest, route: $route, prefix: 'Withdraw Request');
                        }
                    }
                }
            }

            //Withdrawal Method
            $withdrawalMethods = WithdrawalMethod::
                where(function($query) use ($searchKeyword) {
                    $query->where('method_name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhereRaw("JSON_SEARCH(method_fields, 'one', ?) IS NOT NULL", ['%' . $searchKeyword . '%']);
                })
                ->get();

            if ($withdrawalMethods){
                $withdrawalMethodRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'withdraw-method') && str_contains($route->uri(), 'edit');
                });

                if (isset($withdrawalMethodRoutes)) {
                    foreach ($withdrawalMethods as $withdrawalMethod){
                        foreach ($withdrawalMethodRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $withdrawalMethod, route: $route, prefix: 'Withdraw Method');
                        }
                    }
                }
            }

            //Admin Role
            $adminRoles = AdminRole::whereNotIn('id', [1])
                ->where(function($query) use ($searchKeyword) {
                    $query->where('name', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($adminRoles){
                $adminRoleRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'custom-role') && str_contains($route->uri(), 'edit');
                });

                if (isset($adminRoleRoutes)) {
                    foreach ($adminRoles as $adminRole) {
                        foreach ($adminRoleRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $adminRole, route: $route, prefix: 'Employee Role');
                        }
                    }
                }
            }

            //Subscription Package
            $subscriptionPackages = SubscriptionPackage::
                where(function($query) use ($searchKeyword) {
                    $query->where('package_name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('colour', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('text', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($subscriptionPackages){
                $subscriptionPackageRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'package')
                        && (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'details'));
                });

                if (isset($subscriptionPackageRoutes)) {
                    foreach ($subscriptionPackages as $subscriptionPackage) {
                        foreach ($subscriptionPackageRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $subscriptionPackage, route: $route, prefix: 'Subscription');
                        }
                    }
                }
            }

            //Store Subscription
            $storeSubscriptions = StoreSubscription::with('package')
                ->whereHas('package', function ($query) use ($searchKeyword){
                    $query->where('package_name', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('colour', 'LIKE', '%' . $searchKeyword . '%')
                        ->orWhere('text', 'LIKE', '%' . $searchKeyword . '%');
                })
                ->get();

            if ($storeSubscriptions){
                $storeSubscriptionRoutes = $adminRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'subscription') && str_contains($route->uri(), 'subscriber-detail');
                });

                if (isset($storeSubscriptionRoutes)) {
                    foreach ($storeSubscriptions as $storeSubscription) {
                        foreach ($storeSubscriptionRoutes as $route) {
                            $validRoutes[] = $this->filterRoute(model: $storeSubscription, route: $route, name: $storeSubscription?->store?->name, prefix: 'Subscription');
                        }
                    }
                }
            }
        }

        return array_merge($formattedRoutes, $validRoutes);
    }

    private function routeFullUrl($uri)
    {
        return url($uri);
    }

    private function filterRoute($model, $route, $type = null, $name = null, $prefix = null): array
    {
        $uri = $route->uri();
        $routeName = $route->getName();
        $formattedRouteName = ucwords(str_replace(['.', '_'], ' ', Str::afterLast($routeName, '.')));

        preg_match_all('/\{(\w+\??)\}/', $uri, $matches);
        $placeholders = $matches[1];

        $uriWithParameter = $uri;

        if (!empty($placeholders)) {
            $firstPlaceholder = $placeholders[0];
            $uriWithParameter = str_replace("{{$firstPlaceholder}}", $model->id, $uriWithParameter);
        }

        $uriWithParameter = preg_replace('/\{\w+\?\}/', '', $uriWithParameter);
        $uriWithParameter = preg_replace('/\/+/', '/', $uriWithParameter);
        $uriWithParameter = rtrim($uriWithParameter, '/');

        $fullURL = url('/') . '/' . $uriWithParameter;

        if ($type == 'store' && $model->vendor->status == null){
            $fullURL = $formattedRouteName == 'View' ? $fullURL. '/pending-list' : $fullURL;
        }

        if ($type === 'basic-campaign') {
            $baseURL = url('/') . '/admin/campaign/basic';
            $action = $formattedRouteName === 'Edit' ? 'edit' : 'view';
            $fullURL = "{$baseURL}/{$action}/{$model->id}";
            $uriWithParameter = "admin/campaign/basic/{$action}/{$model->id}";
        }

        if ($type === 'item-campaign') {
            $baseURL = url('/') . '/admin/campaign/item';
            $action = $formattedRouteName === 'Edit' ? 'edit' : 'view';
            $fullURL = "{$baseURL}/{$action}/{$model->id}";
            $uriWithParameter = "admin/campaign/item/{$action}/{$model->id}";
        }

        if ($type === 'deliveryMan' && !$model->active) {
            $fullURL = url('/') . '/admin/delivery-man/pending-delivery-man-view/'. $model->id;
            $uriWithParameter = $formattedRouteName === 'Preview' ? "admin/delivery-man/pending-delivery-man-view/{$model->id}" : $uriWithParameter;
        }

//        if ($type == 'order' && $model->subscription_id != null){
//            $fullURL = $formattedRouteName == 'Details' ? url('/') . '/' . 'admin/order/subscription/show/'.$model->id : $fullURL;
//        }

        $routeName = $prefix ? $prefix. ' '. $formattedRouteName : $formattedRouteName;
        $routeName = $name ? $routeName. ' - (' . $name. ')' : $routeName;

        return [
            'routeName' => $routeName,
            'URI' => $uriWithParameter,
            'fullRoute' => $fullURL,
        ];
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeClickedRoute(Request $request): JsonResponse
    {
        $userId = auth('admin')->user()->id;
        $userType = auth('admin')->user()->role_id ? 'admin' : 'admin-employee';
        $routeName = $request->input('routeName');
        $routeUri = $request->input('routeUri');
        $routeFullUrl = $request->input('routeFullUrl');
        $searchKeyword = $request->input('searchKeyword');

        $existingClick = RecentSearch::where('user_id', $userId)
            ->where('user_type', $userType)
            ->where('route_uri', $routeUri)
            ->first();

        if (!$existingClick) {
            $clickedRoute = new RecentSearch();
            $clickedRoute->user_id = $userId;
            $clickedRoute->user_type = $userType;
            $clickedRoute->route_name = $routeName;
            $clickedRoute->route_uri = $routeUri;
            $clickedRoute->route_full_url = isset($searchKeyword) ? $routeFullUrl . '?keyword=' . $searchKeyword : $routeFullUrl;
            $clickedRoute->save();
        }else{
            $existingClick->created_at = now();
            $existingClick->update();
        }

        $userClicksCount = RecentSearch::where('user_id', $userId)
            ->where('user_type', $userType)
            ->count();

        if ($userClicksCount > 15) {
            RecentSearch::where('user_id', $userId)
                ->where('user_type', $userType)
                ->orderBy('created_at', 'asc')
                ->first()
                ->delete();
        }

        return response()->json(['message' => 'Clicked route stored successfully']);
    }

    public function recentSearch(): JsonResponse
    {
        $userId = auth('admin')->user()->id;
        $userType = auth('admin')->user()->role_id ? 'admin' : 'admin-employee';

        $recentSearches = RecentSearch::where('user_id', $userId)
            ->where('user_type', $userType)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($recentSearches);
    }

}

<?php

namespace App\Http\Controllers\Vendor;

use App\Models\AddOn;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Expense;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\RecentSearch;
use App\Models\DeliveryMan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Models\EmployeeRole;
use App\Models\ItemCampaign;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\CentralLogics\Helpers;
use App\Models\VendorEmployee;
use App\Models\WithdrawRequest;
use App\Models\AccountTransaction;
use App\Models\DisbursementDetails;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Models\DisbursementWithdrawalMethod;
use App\Models\Review;
use App\Models\SubscriptionBillingAndRefundHistory;

class SearchRoutingController extends Controller
{
    public function index(Request $request)
    {
        $store_id =  Helpers::get_store_id();
        $vendor_id =  Helpers::get_vendor_id();
        $moduleType = Helpers::get_store_data()->module_type == 'rental';
        $module_id = Helpers::get_store_data()->module_id;
        $searchKeyword = $request->input('search');
        session(['search_keyword' => $searchKeyword]);

        //1st layer
        $formattedRoutes = [];
        $jsonFilePath = public_path('vendor_formatted_routes.json');
        // if (file_exists($jsonFilePath)) {
        //     $fileContents = file_get_contents($jsonFilePath);
        //     $routes = json_decode($fileContents, true);

        //     $excludedUrls = [
        //         'trip',
        //         'vehicle',
        //         'vehicle-category',
        //         'vehicle-brand',
        //         'driver',
        //         'rental-coupon',
        //         'rental-banner',
        //         'rental-reviews',
        //         'trip-report',
        //     ];

        //     foreach ($routes as $route) {
        //         $uri = $route['URI'];
        //         $shouldExclude = false;

        //         foreach ($excludedUrls as $excludedUrl) {
        //             if (Str::contains($uri, $excludedUrl)) {
        //                 $shouldExclude = true;
        //                 break;
        //             }
        //         }

        //         $excludePos = $moduleType && Str::contains($uri, 'pos');

        //         if ($moduleType) {
        //             if ($shouldExclude && !$excludePos) {
        //                 if (Str::contains(strtolower($route['keywords']), strtolower($searchKeyword))) {
        //                     $hasParameters = preg_match('/\{(.*?)\}/', $uri);
        //                     $fullURL = $this->routeFullUrl($uri);

        //                     if (!$hasParameters) {
        //                         $routeName = $route['routeName'];
        //                         $formattedRoutes[] = [
        //                             'routeName' => ucwords($routeName),
        //                             'URI' => $uri,
        //                             'fullRoute' => $fullURL,
        //                         ];
        //                     }
        //                 }
        //             }
        //         } else {
        //             if (!$shouldExclude) {
        //                 if (Str::contains(strtolower($route['keywords']), strtolower($searchKeyword))) {
        //                     $hasParameters = preg_match('/\{(.*?)\}/', $uri);
        //                     $fullURL = $this->routeFullUrl($uri);

        //                     if (!$hasParameters) {
        //                         $routeName = $route['routeName'];
        //                         $formattedRoutes[] = [
        //                             'routeName' => ucwords($routeName),
        //                             'URI' => $uri,
        //                             'fullRoute' => $fullURL,
        //                         ];
        //                     }
        //                 }
        //             }
        //         }
        //     }
        // }

        //2nd layer
        $routes = Route::getRoutes();
        $storeRoutes = collect($routes->getRoutesByMethod()['GET'])->filter(function ($route) {
            return str_starts_with($route->uri(), 'vendor-panel');
        });
        $validRoutes = [];
        if (is_numeric($searchKeyword) &&   $searchKeyword > 0) {

            //order
            $order = Order::where('store_id', $store_id)->Notpos()
                ->NotDigitalOrder()->find($searchKeyword);

            if ($order) {
                $orderRoutes = $storeRoutes->filter(function ($route) {
                    return str_contains($route->uri(), 'order') && str_contains($route->uri(), 'details') && !str_contains($route->uri(), 'pos');
                });
                if (isset($orderRoutes)) {
                    foreach ($orderRoutes as $route) {
                        $validRoutes[] = $this->filterRoute(model: $order, route: $route, type: 'order', prefix: 'Order', name: $order->id);
                    }
                }


                //  //  $ordertransactionRoutes = $storeRoutes->filter(function ($route) {
                // //        return str_contains($route->uri(), 'transaction-report')  && !str_contains($route->uri(), 'transaction-report-export');
                // //    });
                // //    if (isset($ordertransactionRoutes)) {
                // //        foreach ($ordertransactionRoutes as $route) {
                // //            $validRoutes[] = $this->filterRoute(model: $order, route: $route, type: 'order', prefix: 'Order');
                // //        }
                // //    }
                // //    $ragularOrdertReport = $storeRoutes->filter(function ($route) {
                // //        return str_contains($route->uri(), 'order-report')  && !str_contains($route->uri(), 'order-report-export') && !str_contains($route->uri(), 'campaign-order-report');
                // //    });
                // //    if (isset($ragularOrdertReport)) {
                // //        foreach ($ragularOrdertReport as $route) {
                // //            $validRoutes[] = $this->filterRoute(model: $order, route: $route, type: 'order', prefix: 'Order');
                // //        }
                // //    }

            }

            // // $order = Order::where('store_id', $store_id)->Notpos()
            // //        ->NotDigitalOrder()->whereHas('details', function ($query) {
            // //            $query->whereNotNull('item_campaign_id');
            // //        })->find($searchKeyword);
            // //    if ($order) {
            // //        $campaignOrdertReport = $storeRoutes->filter(function ($route) {
            // //            return str_contains($route->uri(), 'campaign-order-report')   && !str_contains($route->uri(), 'order-report-export');
            // //        });
            // //        if (isset($campaignOrdertReport)) {
            // //            foreach ($campaignOrdertReport as $route) {
            // //                $validRoutes[] = $this->filterRoute(model: $order, route: $route, type: 'order', prefix: 'Order');
            // //            }
            // //        }
            // //    }

                    //     category
                       $category = Category::where('module_id', $module_id)->find($searchKeyword);
                       if ($category) {
                           $categoryRoutes = $storeRoutes->filter(function ($route) {
                               return  !str_contains($route->uri(), '-category/list') &&  (str_contains($route->uri(), 'category/list') || str_contains($route->uri(), 'category/sub-category-list'));
                           });

                           if (isset($categoryRoutes)) {
                               foreach ($categoryRoutes as $route) {
                                   $validRoutes[] = $this->filterRoute(model: $category, route: $route, type: 'category', prefix: 'Category' , name: $category->name ,searchKeyword: $category->name);
                               }
                           }
                       }


            //
            //            // //AddOn
            //            $addOn = AddOn::where('store_id', $store_id)->find($searchKeyword);
            //            if ($addOn) {
            //                $addOnRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'addon') && str_contains($route->uri(), 'edit');
            //                });
            //
            //                if (isset($addOnRoutes)) {
            //                    foreach ($addOnRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $addOn, route: $route, prefix: 'Addon');
            //                    }
            //                }
            //            }
            //
            //            //food
            //            $food = Food::where('store_id', $store_id)->find($searchKeyword);
            //            if ($food) {
            //                $foodRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'food')
            //                        && (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'view'))
            //                        && !str_contains($route->uri(), 'status') && !str_contains($route->uri(), 'food-wise')
            //                        && !str_contains($route->uri(), 'reviews-export');
            //                });
            //
            //                if (isset($foodRoutes)) {
            //                    foreach ($foodRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $food, route: $route, type: 'food', prefix: 'Food');
            //                    }
            //                }
            //            }
            //
            //            //item campaign
            //            $itemCampaign = ItemCampaign::where('store_id', $store_id)->find($searchKeyword);
            //            if ($itemCampaign) {
            //                $itemCampaignRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'campaign/item/list') ;
            //                });
            //
            //                if (isset($itemCampaignRoutes)) {
            //                    foreach ($itemCampaignRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $itemCampaign, route: $route, type: 'item-campaign', prefix: 'Item Campaign');
            //                    }
            //                }
            //            }
            //
            //            //coupon
            //            $coupon = Coupon::where('store_id', $store_id)->find($searchKeyword);
            //            if ($coupon) {
            //                $couponRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'coupon/add-new') || str_contains($route->uri(), 'coupon/update');
            //                });
            //
            //                if (isset($couponRoutes)) {
            //                    foreach ($couponRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $coupon, route: $route, prefix: 'Coupon');
            //                    }
            //                }
            //            }
            //
            //
            //
            //            //Advertisement
            //            $ads = Advertisement::where('store_id', $store_id)->find($searchKeyword);
            //            if ($ads) {
            //                $adsRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'advertisement')
            //                        && (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'detail'));
            //                });
            //                if (isset($adsRoutes)) {
            //                    foreach ($adsRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $ads, route: $route, prefix: 'Advertisement');
            //                    }
            //                }
            //            }
            //
            //            //delivery man
            //            $deliveryMan = DeliveryMan::where('store_id', $store_id)->find($searchKeyword);
            //            if ($deliveryMan) {
            //                $deliveryManRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'delivery-man')
            //                        && str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'preview');
            //                });
            //                if (isset($deliveryManRoutes)) {
            //                    foreach ($deliveryManRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $deliveryMan, route: $route, type: 'deliveryMan', prefix: 'Delivery Man');
            //                    }
            //                }
            //            }
            //
            //            //store disbursement
            //            $storeDisbursement = DisbursementDetails::where('store_id', $store_id)->where('disbursement_id', $searchKeyword)->first();
            //            if ($storeDisbursement) {
            //                $storeDisbursementRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'disbursement-report') && !str_contains($route->uri(), 'disbursement-report-export');
            //                });
            //
            //                if (isset($storeDisbursementRoutes)) {
            //                    foreach ($storeDisbursementRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $storeDisbursement, route: $route, prefix: 'Store Disbursement');
            //                    }
            //                }
            //            }
            //
            //            //withdraw requests
            //            $withdrawRequest = WithdrawRequest::where('vendor_id', $vendor_id)->find($searchKeyword);
            //            if ($withdrawRequest) {
            //                $withdrawRequestRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'wallet') && !str_contains($route->uri(), 'disbursement-list')  && !str_contains($route->uri(), 'wallet-payment-list') && !str_contains($route->uri(), 'method-list') && !str_contains($route->uri(), 'export');
            //                });
            //
            //                if (isset($withdrawRequestRoutes)) {
            //                    foreach ($withdrawRequestRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $withdrawRequest, route: $route, prefix: 'Withdraw Request');
            //                    }
            //                }
            //            }
            //            //Account transaction
            //            $accountTransaction = AccountTransaction::where('type', 'collected')
            //                ->where('created_by', 'store')
            //                ->where('from_id', Helpers::get_vendor_id())
            //                ->where('from_type', 'store')
            //                ->find($searchKeyword);
            //            if ($accountTransaction) {
            //                $accountTransactionRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'wallet-payment-list');
            //                });
            //
            //                if (isset($accountTransactionRoutes)) {
            //                    foreach ($accountTransactionRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $withdrawRequest, route: $route, prefix: 'Withdraw Request');
            //                    }
            //                }
            //            }
            //
            //            //withdraw method
            //            $withdrawalMethod = DisbursementWithdrawalMethod::where('store_id', $store_id)->find($searchKeyword);
            //            if ($withdrawalMethod) {
            //                $withdrawalMethodRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'withdraw-method') && str_contains($route->uri(), 'edit');
            //                });
            //
            //                if (isset($withdrawalMethodRoutes)) {
            //                    foreach ($withdrawalMethodRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $withdrawalMethod, route: $route, prefix: 'Withdraw Method');
            //                    }
            //                }
            //            }
            //
            //            //Employee role
            //            $vendorRole = EmployeeRole::where('store_id', $store_id)->find($searchKeyword);
            //            if ($vendorRole) {
            //                $vendorRoleRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'custom-role') && str_contains($route->uri(), 'edit');
            //                });
            //
            //                if (isset($vendorRoleRoutes)) {
            //                    foreach ($vendorRoleRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $vendorRole, route: $route, prefix: 'Employee Role');
            //                    }
            //                }
            //            }
            //
            //            //Employee
            //            $employee = VendorEmployee::where('store_id', $store_id)->find($searchKeyword);
            //            if ($employee) {
            //                $employeeRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'employee') && str_contains($route->uri(), 'update');
            //                });
            //
            //                if (isset($employeeRoutes)) {
            //                    foreach ($employeeRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $employee, route: $route, prefix: 'Employee');
            //                    }
            //                }
            //            }
            //
            //
            //            //subscriber
            //            $storeSubscription = StoreSubscription::where('store_id', $store_id)->find($searchKeyword);
            //            if ($storeSubscription) {
            //                $storeSubscriptionRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'subscriber-detail');
            //                });
            //
            //                if (isset($storeSubscriptionRoutes)) {
            //                    foreach ($storeSubscriptionRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $storeSubscription, route: $route, prefix: 'Subscription');
            //                    }
            //                }
            //            }
            //            //subscriber
            //            $storeSubscriptionBillingAndRefundHistory = SubscriptionBillingAndRefundHistory::where('store_id', $store_id)->find($searchKeyword);
            //            if ($storeSubscriptionBillingAndRefundHistory) {
            //                $storeSubscriptionBillingAndRefundHistoryRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'subscriber-wallet-transactions');
            //                });
            //
            //                if (isset($storeSubscriptionBillingAndRefundHistoryRoutes)) {
            //                    foreach ($storeSubscriptionBillingAndRefundHistoryRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $storeSubscriptionBillingAndRefundHistory, route: $route, prefix: 'Subscription');
            //                    }
            //                }
            //            }
            //            //expense report
            //            $expense = Expense::where('store_id', $store_id)->where('created_by', 'vendor')->find($searchKeyword);
            //            if ($expense) {
            //                $expenseRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'expense-report');
            //                });
            //
            //                if (isset($expenseRoutes)) {
            //                    foreach ($expenseRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $expense, route: $route, prefix: null);
            //                    }
            //                }
            //            }
            //            //review
            //            $review = Review::where('store_id', $store_id)->find($searchKeyword);
            //            if ($review) {
            //                $reviewRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'review');
            //                });
            //
            //                if (isset($reviewRoutes)) {
            //                    foreach ($reviewRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $review, route: $route, prefix: null);
            //                    }
            //                }
            //            }
        } else {
            //            //Store
            //            $store = Store::where('id', $store_id)->where('name', 'LIKE', '%' . $searchKeyword . '%')
            //                ->orWhere('phone', 'LIKE', '%' . $searchKeyword . '%')
            //                ->orWhere('email', 'LIKE', '%' . $searchKeyword . '%')
            //                ->orWhere('address', 'LIKE', '%' . $searchKeyword . '%')
            //                ->orWhere('meta_title', 'LIKE', '%' . $searchKeyword . '%')
            //                ->orWhere('meta_description', 'LIKE', '%' . $searchKeyword . '%')
            //                ->orWhereHas('vendor', function ($query) use ($searchKeyword) {
            //                    return $query->where('f_name', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('l_name', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('email', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('phone', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhereRaw("CONCAT(f_name, ' ', l_name) LIKE ?", ['%' . $searchKeyword . '%'])
            //                        ->orWhereRaw("CONCAT(l_name, ' ', f_name) LIKE ?", ['%' . $searchKeyword . '%'])
            //                        ->orWhereRaw("CONCAT(l_name,f_name) LIKE ?", ['%' . $searchKeyword . '%'])
            //                        ->orWhereRaw("CONCAT(f_name,l_name) LIKE ?", ['%' . $searchKeyword . '%']);
            //                })
            //                ->first();
            //
            //            if ($store) {
            //                $storeUrlRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'business-settings/store-setup');
            //                });
            //
            //                if (isset($storeUrlRoutes)) {
            //                    foreach ($storeUrlRoutes as $route) {
            //                        $validRoutes[] = $this->filterRoute(model: $store, route: $route, type: 'store', prefix: 'Store');
            //                    }
            //                }
            //            }
            //
            //            //Order
            //            $orders = Order::where('store_id', $store_id)
            //                ->Notpos()
            //                ->NotDigitalOrder()
            //
            //                ->with('customer')->where(function ($query) use ($searchKeyword) {
            //                    $query->whereHas('customer', function ($query) use ($searchKeyword) {
            //                        $query->where('f_name', 'LIKE', '%' . $searchKeyword . '%')
            //                            ->orWhere('l_name', 'LIKE', '%' . $searchKeyword . '%')
            //                            ->orWhere('email', 'LIKE', '%' . $searchKeyword . '%')
            //                            ->orWhere('phone', 'LIKE', '%' . $searchKeyword . '%')
            //                            ->orWhereRaw("CONCAT(f_name, ' ', l_name) LIKE ?", ['%' . $searchKeyword . '%'])
            //                            ->orWhereRaw("CONCAT(f_name,l_name) LIKE ?", ['%' . $searchKeyword . '%'])
            //                            ->orWhereRaw("CONCAT(l_name,f_name) LIKE ?", ['%' . $searchKeyword . '%']);
            //                    })
            //                        ->orwhere(function ($query) use ($searchKeyword) {
            //                            $query->WhereRaw("JSON_SEARCH(delivery_address, 'one', ?) IS NOT NULL", ['%' . $searchKeyword . '%']);
            //                        });
            //                })->get();
            //
            //
            //            if ($orders) {
            //                $ordersRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'order') && str_contains($route->uri(), 'details') && !str_contains($route->uri(), 'pos');
            //                });
            //
            //                if (isset($ordersRoutes)) {
            //                    foreach ($orders as $order) {
            //                        foreach ($ordersRoutes as $route) {
            //
            //                            $customerName = $order?->customer ? $order?->customer?->f_name . ' ' . $order?->customer?->l_name : json_decode($order->delivery_address, true)['contact_person_name'];
            //                            $validRoutes[] = $this->filterRoute(model: $order, route: $route, type: 'order', prefix: 'Order', name: $customerName);
            //                        }
            //                    }
            //                }
            //            }
            //
            //            //Category
            //            $categories = Category::where(function ($query) use ($searchKeyword) {
            //                    $query->where('name', 'LIKE', '%' . $searchKeyword . '%');
            //                })
            //                ->get();
            //
            //            if ($categories) {
            //                $categoryRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'category') && !str_contains($route->uri(), 'edit') && !str_contains($route->uri(), 'get-all');
            //                });
            //
            //                if (isset($categoryRoutes)) {
            //                    foreach ($categories as $category) {
            //                        foreach ($categoryRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $category, route: $route, type: 'category', prefix: 'Category');
            //                        }
            //                    }
            //                }
            //            }
            //
            //            //AddOn
            //            $addOns = AddOn::where('store_id', $store_id)
            //                ->where(function ($query) use ($searchKeyword) {
            //                    $query->where('name', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('stock_type', 'LIKE', '%' . $searchKeyword . '%');
            //                })
            //                ->get();
            //
            //            if ($addOns) {
            //                $addOnRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'addon') && str_contains($route->uri(), 'edit');
            //                });
            //
            //                if (isset($addOnRoutes)) {
            //                    foreach ($addOns as $addOn) {
            //                        foreach ($addOnRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $addOn, route: $route, prefix: 'Addon');
            //                        }
            //                    }
            //                }
            //            }
            //
            //            //Food
            //            $foods = Food::where('store_id', $store_id)
            //                ->where(function ($query) use ($searchKeyword) {
            //                    $query->where('name', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('description', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('stock_type', 'LIKE', '%' . $searchKeyword . '%');
            //                })
            //                ->get();
            //
            //            if ($foods) {
            //                $foodRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'food')
            //                        && (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'view'))
            //                        && !str_contains($route->uri(), 'status') && !str_contains($route->uri(), 'food-wise')
            //                        && !str_contains($route->uri(), 'reviews-export');
            //                });
            //
            //                if (isset($foodRoutes)) {
            //                    foreach ($foods as $food) {
            //                        foreach ($foodRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $food, route: $route, type: 'food', prefix: 'Food');
            //                        }
            //                    }
            //                }
            //            }
            //
            //            //Campaign
            //            $campaigns = Campaign::running()->active()->latest()
            //                ->where(function ($query) use ($searchKeyword) {
            //                    $query->where('title', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('description', 'LIKE', '%' . $searchKeyword . '%');
            //                })
            //                ->get();
            //
            //            if ($campaigns) {
            //                $campaignRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'campaign/list');
            //                });
            //
            //                if (isset($campaignRoutes)) {
            //                    foreach ($campaigns as $campaign) {
            //                        foreach ($campaignRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $campaign, route: $route, type: 'basic-campaign', prefix: 'Basic Campaign');
            //                        }
            //                    }
            //                }
            //            }
            //
            //            //ItemCampaign
            //            $itemCampaigns = ItemCampaign::where('store_id', $store_id)
            //                ->where(function ($query) use ($searchKeyword) {
            //                    $query->where('title', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('description', 'LIKE', '%' . $searchKeyword . '%');
            //                })
            //                ->get();
            //
            //            if ($itemCampaigns) {
            //                $itemCampaignRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'campaign/item/list') ;
            //                });
            //
            //                if (isset($itemCampaignRoutes)) {
            //                    foreach ($itemCampaigns as $itemCampaign) {
            //                        foreach ($itemCampaignRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $itemCampaign, route: $route, type: 'item-campaign', prefix: 'Item Campaign');
            //                        }
            //                    }
            //                }
            //            }
            //
            //            //Coupon
            //            $coupons = Coupon::where('store_id', $store_id)
            //                ->where(function ($query) use ($searchKeyword) {
            //                    $query->where('title', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('code', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('discount_type', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('coupon_type', 'LIKE', '%' . $searchKeyword . '%');
            //                })
            //                ->get();
            //
            //            if ($coupons) {
            //                $couponRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'coupon/add-new') || str_contains($route->uri(), 'coupon/update');
            //                });
            //
            //                if (isset($couponRoutes)) {
            //                    foreach ($coupons as $coupon) {
            //                        foreach ($couponRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $coupon, route: $route, prefix: 'Coupon');
            //                        }
            //                    }
            //                }
            //            }
            //
            //            //Advertisement
            //            $advertisements = Advertisement::where('store_id', $store_id)
            //                ->where(function ($query) use ($searchKeyword) {
            //                    $query->where('title', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('description', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('add_type', 'LIKE', '%' . $searchKeyword . '%');
            //                })
            //                ->get();
            //
            //            if ($advertisements) {
            //                $adsRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'advertisement')
            //                        && (str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'detail'));
            //                });
            //                if (isset($adsRoutes)) {
            //                    foreach ($advertisements as $advertisement) {
            //                        foreach ($adsRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $advertisement, route: $route, prefix: 'Advertisement');
            //                        }
            //                    }
            //                }
            //            }
            //
            //
            //            //DeliveryMan
            //            $deliveryMen = DeliveryMan::where('store_id', $store_id)
            //                ->where(function ($query) use ($searchKeyword) {
            //                    $query->where('f_name', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('l_name', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('email', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('phone', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('identity_type', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhereRaw("CONCAT(f_name, ' ', l_name) LIKE ?", ['%' . $searchKeyword . '%'])
            //                        ->orWhereRaw("CONCAT(f_name,l_name) LIKE ?", ['%' . $searchKeyword . '%'])
            //                        ->orWhereRaw("CONCAT(l_name,f_name) LIKE ?", ['%' . $searchKeyword . '%']);
            //                })
            //                ->get();
            //
            //            if ($deliveryMen) {
            //                $deliveryManRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'delivery-man')
            //                        && str_contains($route->uri(), 'edit') || str_contains($route->uri(), 'preview');
            //                });
            //                if (isset($deliveryManRoutes)) {
            //                    foreach ($deliveryMen as $deliveryMan) {
            //                        foreach ($deliveryManRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $deliveryMan, route: $route, type: 'deliveryMan', name: $deliveryMan->f_name . ' ' . $deliveryMan->l_name, prefix: 'Delivery Man');
            //                        }
            //                    }
            //                }
            //            }
            //
            //
            //            //Withdraw Request
            //            $withdrawRequests = WithdrawRequest::where('vendor_id', $vendor_id)
            //                ->where(function ($query) use ($searchKeyword) {
            //                    $query->where('type', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('withdrawal_method_fields->account_name', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('withdrawal_method_fields->account_number', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('withdrawal_method_fields->email', 'LIKE', '%' . $searchKeyword . '%');
            //                })
            //                ->get();
            //
            //            if ($withdrawRequests) {
            //                $withdrawRequestRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'wallet') && !str_contains($route->uri(), 'disbursement-list')  && !str_contains($route->uri(), 'wallet-payment-list') && !str_contains($route->uri(), 'method-list') && !str_contains($route->uri(), 'export');
            //                });
            //
            //                if (isset($withdrawRequestRoutes)) {
            //                    foreach ($withdrawRequests as $withdrawRequest) {
            //                        foreach ($withdrawRequestRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $withdrawRequest, route: $route, prefix: 'Withdraw Request');
            //                        }
            //                    }
            //                }
            //            }
            //
            //            //accountTransaction Request
            //            $accountTransaction = AccountTransaction::where('type', 'collected')
            //                ->where('created_by', 'store')
            //                ->where('from_id', Helpers::get_vendor_id())
            //                ->where('from_type', 'store')
            //
            //
            //                ->where(function ($query) use ($searchKeyword) {
            //                    $query->where('method', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('ref', 'LIKE', '%' . $searchKeyword . '%');
            //                })
            //                ->get();
            //
            //            if ($accountTransaction) {
            //                $accountTransactionRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'wallet-payment-list');
            //                });
            //
            //                if (isset($accountTransactionRoutes)) {
            //                    foreach ($accountTransaction as $accountTransactionRequest) {
            //                        foreach ($accountTransactionRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $accountTransactionRequest, route: $route, prefix: 'Withdraw Request');
            //                        }
            //                    }
            //                }
            //            }
            //
            //            //Withdrawal Method
            //            $withdrawalMethods = DisbursementWithdrawalMethod::where('store_id', $store_id)
            //                ->where(function ($query) use ($searchKeyword) {
            //                    $query->where('method_name', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhereRaw("JSON_SEARCH(method_fields, 'one', ?) IS NOT NULL", ['%' . $searchKeyword . '%']);
            //                })
            //                ->get();
            //
            //            if ($withdrawalMethods) {
            //                $withdrawalMethodRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'withdraw-method') && str_contains($route->uri(), 'edit');
            //                });
            //
            //                if (isset($withdrawalMethodRoutes)) {
            //                    foreach ($withdrawalMethods as $withdrawalMethod) {
            //                        foreach ($withdrawalMethodRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $withdrawalMethod, route: $route, prefix: 'Withdraw Method');
            //                        }
            //                    }
            //                }
            //            }
            //
            //            //vendor Role
            //            $vendorRoles = EmployeeRole::where('store_id', $store_id)
            //                ->where(function ($query) use ($searchKeyword) {
            //                    $query->where('name', 'LIKE', '%' . $searchKeyword . '%');
            //                })
            //                ->get();
            //
            //            if ($vendorRoles) {
            //                $vendorRoleRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'custom-role') && str_contains($route->uri(), 'edit');
            //                });
            //
            //                if (isset($vendorRoleRoutes)) {
            //                    foreach ($vendorRoles as $vendorRole) {
            //                        foreach ($vendorRoleRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $vendorRole, route: $route, prefix: 'Employee Role');
            //                        }
            //                    }
            //                }
            //            }
            //
            //            $vendorEmployee = VendorEmployee::where('store_id', $store_id)
            //                ->where(function ($query) use ($searchKeyword) {
            //                    $query->where('f_name', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('l_name', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('phone', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('email', 'LIKE', '%' . $searchKeyword . '%');
            //                })
            //                ->get();
            //
            //            if ($vendorEmployee) {
            //                $adminRoleRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'custom-role') && str_contains($route->uri(), 'edit');
            //                });
            //
            //                if (isset($adminRoleRoutes)) {
            //                    foreach ($vendorEmployee as $adminRole) {
            //                        foreach ($adminRoleRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $adminRole, route: $route, prefix: 'Employee');
            //                        }
            //                    }
            //                }
            //            }
            //
            //            //Store Subscription
            //            $storeSubscriptions = StoreSubscription::with('package')->where('store_id', $store_id)
            //                ->whereHas('package', function ($query) use ($searchKeyword) {
            //                    $query->where('package_name', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('text', 'LIKE', '%' . $searchKeyword . '%');
            //                })
            //                ->get();
            //
            //            if ($storeSubscriptions) {
            //                $storeSubscriptionRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'subscription') && str_contains($route->uri(), 'subscriber-detail');
            //                });
            //
            //                if (isset($storeSubscriptionRoutes)) {
            //                    foreach ($storeSubscriptions as $storeSubscription) {
            //                        foreach ($storeSubscriptionRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $storeSubscription, route: $route, name: $storeSubscription?->store?->name, prefix: 'Subscription');
            //                        }
            //                    }
            //                }
            //            }
            //
            //            //subscriber billing and refund history
            //            $storeSubscriptionBillingAndRefundHistorys = SubscriptionBillingAndRefundHistory::where('store_id', $store_id)
            //                ->where(function ($query) use ($searchKeyword) {
            //                    $query->where('transaction_type', 'LIKE', '%' . $searchKeyword . '%')
            //                        ->orWhere('reference', 'LIKE', '%' . $searchKeyword . '%');
            //                })
            //                ->get();
            //
            //
            //            if ($storeSubscriptionBillingAndRefundHistorys) {
            //                $storeSubscriptionBillingAndRefundHistoryRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'subscriber-wallet-transactions');
            //                });
            //
            //                if (isset($storeSubscriptionBillingAndRefundHistoryRoutes)) {
            //                    foreach ($storeSubscriptionBillingAndRefundHistorys as $history) {
            //                        foreach ($storeSubscriptionBillingAndRefundHistoryRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $history, route: $route, prefix: 'Subscription');
            //                        }
            //                    }
            //                }
            //            }
            //
            //            //expense report
            //            $expense = Expense::where('store_id', $store_id)->where('created_by', 'vendor')->where(function ($query) use ($searchKeyword) {
            //                $query->where('type', 'LIKE', '%' . $searchKeyword . '%')
            //                    ->orWhere('order_id', 'LIKE', '%' . $searchKeyword . '%');
            //            })
            //                ->get();
            //            if ($expense) {
            //                $expenseRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'expense-report');
            //                });
            //
            //                if (isset($expenseRoutes)) {
            //                    foreach ($expense as $expens) {
            //                        foreach ($expenseRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $expens, route: $route, prefix: null);
            //                        }
            //                    }
            //                }
            //            }
            //
            //
            //            $reviews = Review::where('store_id', $store_id)->where(function ($query) use ($searchKeyword) {
            //                $query->where('comment', 'LIKE', '%' . $searchKeyword . '%')
            //                    ->orWhere('reply', 'LIKE', '%' . $searchKeyword . '%');
            //            })
            //                ->get();
            //
            //
            //            if ($reviews) {
            //                $reviewRoutes = $storeRoutes->filter(function ($route) {
            //                    return str_contains($route->uri(), 'review');
            //                });
            //
            //                if (isset($reviewRoutes)) {
            //                    foreach ($reviews as $review) {
            //                        foreach ($reviewRoutes as $route) {
            //                            $validRoutes[] = $this->filterRoute(model: $review, route: $route, prefix: null);
            //                        }
            //                    }
            //                }
            //            }
        }

        return array_merge($formattedRoutes, $validRoutes);
    }

    private function routeFullUrl($uri)
    {
        return url($uri);
    }

    private function filterRoute($model, $route, $type = null, $name = null, $prefix = null ,$searchKeyword = null): array
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


        if($searchKeyword &&  !is_numeric($searchKeyword)){
            $uriWithParameter .= '?search=' . urlencode($searchKeyword);
        }
        $fullURL = url('/') . '/' . $uriWithParameter;
        $routeName = $prefix ? $prefix . ' ' . $formattedRouteName : $formattedRouteName;
        $routeName = $name ? $routeName . ' - (' . $name . ')' : $routeName;

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
        $userId = Helpers::get_vendor_id();
        $userType = auth('vendor')->check() ? 'vendor' : 'vendor-employee';
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
            $clickedRoute->module_id = Helpers::get_store_data()->module_id;
            $clickedRoute->keyword = $searchKeyword;
            $clickedRoute->route_full_url = isset($searchKeyword) ? $routeFullUrl . '?keyword=' . $searchKeyword : $routeFullUrl;
            $clickedRoute->save();
        } else {
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
        $userId = Helpers::get_vendor_id();
        $userType = auth('vendor')->check() ? 'vendor' : 'vendor-employee';

        $recentSearches = RecentSearch::where('user_id', $userId)
            ->where('user_type', $userType)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($recentSearches);
    }
}

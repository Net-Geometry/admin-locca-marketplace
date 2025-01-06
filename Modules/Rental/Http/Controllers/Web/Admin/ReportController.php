<?php

namespace Modules\Rental\Http\Controllers\Web\Admin;

use App\CentralLogics\Helpers;
use App\Models\Item;
use App\Models\Store;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Rental\Entities\TripTransaction;
use Modules\Rental\Exports\TransactionReportExport;
use Modules\Rental\Exports\VehicleReviewExport;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportController extends Controller
{
    public function trip_index()
    {
        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }
        return view('rental::admin.report.trip-index');
    }

    public function transactionReport(Request $request)
    {
        $key = explode(' ', $request['search']);

        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }
        $from = session('from_date');
        $to = session('to_date');
        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider_id = $request->query('provider_id', 'all');
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $filter = $request->query('filter', 'all_time');

        $tripTransactions = TripTransaction::with('trip', 'trip.trip_details', 'trip.customer', 'trip.provider')->when(isset($zone), function ($query) use ($zone) {
            return $query->where('zone_id', $zone->id);
        })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('trip_id', 'like', "%{$value}%");
                    }
                });
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(request('module_id'), function ($query) {
                return $query->module(request('module_id'));
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })->orderBy('created_at', 'desc')
            ->paginate(config('default_pagination'))->withQueryString();

        $adminEarned = TripTransaction::with('trip', 'trip.trip_details', 'trip.customer', 'trip.provider')->when(isset($zone), function ($query) use ($zone) {
            return $query->where('zone_id', $zone->id);
        })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('trip_id', 'like', "%{$value}%");
                    }
                });
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(request('module_id'), function ($query) {
                return $query->module(request('module_id'));
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->sum(DB::raw('admin_commission'));

        $providerEarned = TripTransaction::with('trip', 'trip.trip_details', 'trip.customer', 'trip.provider')->when(isset($zone), function ($query) use ($zone) {
            return $query->where('zone_id', $zone->id);
        })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('trip_id', 'like', "%{$value}%");
                    }
                });
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(request('module_id'), function ($query) {
                return $query->module(request('module_id'));
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->sum(DB::raw('store_amount'));

        $totalAmount = TripTransaction::with('trip', 'trip.trip_details', 'trip.customer', 'trip.provider')->when(isset($zone), function ($query) use ($zone) {
            return $query->where('zone_id', $zone->id);
        })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('trip_id', 'like', "%{$value}%");
                    }
                });
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(request('module_id'), function ($query) {
                return $query->module(request('module_id'));
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->sum(DB::raw('trip_amount'));

        return view('rental::admin.report.transaction-report', compact('tripTransactions', 'zone', 'provider', 'filter', 'adminEarned', 'providerEarned','key','totalAmount'));
    }
    public function transactionExport(Request $request)
    {
        $key = explode(' ', $request['search']);

        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }
        $from = session('from_date');
        $to = session('to_date');
        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider_id = $request->query('provider_id', 'all');
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $filter = $request->query('filter', 'all_time');

        $tripTransactions = TripTransaction::with('trip', 'trip.trip_details', 'trip.customer', 'trip.provider')->when(isset($zone), function ($query) use ($zone) {
            return $query->where('zone_id', $zone->id);
        })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('trip_id', 'like', "%{$value}%");
                    }
                });
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(request('module_id'), function ($query) {
                return $query->module(request('module_id'));
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })->orderBy('created_at', 'desc')
            ->get();

        $adminEarned = TripTransaction::with('trip', 'trip.trip_details', 'trip.customer', 'trip.provider')->when(isset($zone), function ($query) use ($zone) {
            return $query->where('zone_id', $zone->id);
        })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('trip_id', 'like', "%{$value}%");
                    }
                });
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(request('module_id'), function ($query) {
                return $query->module(request('module_id'));
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->sum(DB::raw('admin_commission'));

        $providerEarned = TripTransaction::with('trip', 'trip.trip_details', 'trip.customer', 'trip.provider')->when(isset($zone), function ($query) use ($zone) {
            return $query->where('zone_id', $zone->id);
        })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('trip_id', 'like', "%{$value}%");
                    }
                });
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(request('module_id'), function ($query) {
                return $query->module(request('module_id'));
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->sum(DB::raw('store_amount'));

        $totalAmount = TripTransaction::with('trip', 'trip.trip_details', 'trip.customer', 'trip.provider')->when(isset($zone), function ($query) use ($zone) {
            return $query->where('zone_id', $zone->id);
        })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('trip_id', 'like', "%{$value}%");
                    }
                });
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(request('module_id'), function ($query) {
                return $query->module(request('module_id'));
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->sum(DB::raw('trip_amount'));

        $data = [
            'tripTransactions'=>$tripTransactions,
            'search'=>$request->search??null,
            'from'=>(($filter == 'custom') && $from)?$from:null,
            'to'=>(($filter == 'custom') && $to)?$to:null,
            'zone'=>is_numeric($zone_id)?Helpers::get_zones_name($zone_id):null,
            'provider'=>is_numeric($provider_id)?Helpers::get_stores_name($provider_id):null,
            'adminEarned'=>$adminEarned,
            'providerEarned'=>$providerEarned,
            'totalAmount'=>$totalAmount,
            'filter'=>$filter,
        ];

        if ($request->type == 'excel') {
            return Excel::download(new TransactionReportExport($data), 'TransactionReport.xlsx');
        } else if ($request->type == 'csv') {
            return Excel::download(new TransactionReportExport($data), 'TransactionReport.csv');
        }
    }

    public function item_wise_report(Request $request)
    {
        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $provider_id = $request->query('provider_id', 'all');
        $category_id = $request->query('category_id', 'all');
        $filter = $request->query('filter', 'all_time');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $category = is_numeric($category_id) ? Category::findOrFail($category_id) : null;
        $items = $this->get_item_data($request);
        $items =  $items->paginate(config('default_pagination'))->withQueryString();
        return view('rental::admin.report.item-wise-report', compact('zone', 'provider', 'category', 'items', 'filter'));
    }
    public function item_wise_export(Request $request)
    {
        if (session()->has('from_date') == false) {
            session()->put('from_date', now()->firstOfMonth()->format('Y-m-d'));
            session()->put('to_date', now()->lastOfMonth()->format('Y-m-d'));
        }
        $from = session('from_date');
        $to = session('to_date');

        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $provider_id = $request->query('provider_id', 'all');
        $category_id = $request->query('category_id', 'all');
        $filter = $request->query('filter', 'all_time');
        $items = $this->get_item_data($request);
        $items =  $items->get();

        $data = [
            'items'=>$items,
            'search'=>$request->search??null,
            'from'=>(($filter == 'custom') && $from)?$from:null,
            'to'=>(($filter == 'custom') && $to)?$to:null,
            'zone'=>is_numeric($zone_id)?Helpers::get_zones_name($zone_id):null,
            'provider'=>is_numeric($provider_id)?Helpers::get_providers_name($provider_id):null,
            'category'=>is_numeric($category_id)?Helpers::get_category_name($category_id):null,
            'module'=>request('module_id')?Helpers::get_module_name(request('module_id')):null,
            'filter'=>$filter,
        ];

        if ($request->type == 'excel') {
            return Excel::download(new ItemReportExport($data), 'ItemReport.xlsx');
        } else if ($request->type == 'csv') {
            return Excel::download(new ItemReportExport($data), 'ItemReport.csv');
        }
    }


    private static function get_item_data($request){

        $key = explode(' ', $request['search']);
        if (session()->has('from_date') == false) {
            session()->put('from_date', now()->firstOfMonth()->format('Y-m-d'));
            session()->put('to_date', now()->lastOfMonth()->format('Y-m-d'));
        }
        $from = session('from_date');
        $to = session('to_date');

        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $provider_id = $request->query('provider_id', 'all');
        $category_id = $request->query('category_id', 'all');
        $filter = $request->query('filter', 'all_time');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $category = is_numeric($category_id) ? Category::findOrFail($category_id) : null;

        $items =Item::withoutGlobalScope(StoreScope::class)
            ->withCount([
                'trips' => function ($query) use ($from, $to, $filter) {
                    $query->whereHas('trip', function ($query) {
                        return $query->whereIn('trip_status', ['delivered', 'refund_requested', 'refund_request_canceled']);
                    })->applyDateFilter($filter, $from, $to);
                },
            ])
            ->withSum([
                'trips' => function ($query) use ($from, $to, $filter) {
                    $query->whereHas('trip', function ($query) {
                        return $query->whereIn('trip_status', ['delivered', 'refund_requested', 'refund_request_canceled']);
                    })->applyDateFilter($filter, $from, $to);
                },
            ], 'quantity')

            ->addSelect([
                'total_discount' => function ($query) use ($from, $to, $filter) {
                    $query->selectRaw('SUM(trip_details.discount_on_item * trip_details.quantity)')
                        ->from('trip_details')
                        ->join('trips', 'trips.id', '=', 'trip_details.trip_id')
                        ->whereColumn('trip_details.item_id', 'items.id')
                        ->whereIn('trips.trip_status', ['delivered', 'refund_requested', 'refund_request_canceled'])
                        ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                            return $query->whereBetween('trip_details.created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                        })
                        ->when(isset($filter) && $filter == 'this_year', function ($query) {
                            return $query->whereYear('trip_details.created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'this_month', function ($query) {
                            return $query->whereMonth('trip_details.created_at', now()->format('m'))
                                ->whereYear('trip_details.created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                            return $query->whereYear('trip_details.created_at', date('Y') - 1);
                        })
                        ->when(isset($filter) && $filter == 'this_week', function ($query) {
                            return $query->whereBetween('trip_details.created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]); // Filter by this week
                        });
                },
                'trips_sum_price' => function ($query) use ($from, $to, $filter) {
                    $query->selectRaw('SUM(trip_details.price * trip_details.quantity)')
                        ->from('trip_details')
                        ->join('trips', 'trips.id', '=', 'trip_details.trip_id')
                        ->whereColumn('trip_details.item_id', 'items.id')
                        ->whereIn('trips.trip_status', ['delivered', 'refund_requested', 'refund_request_canceled'])
                        ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                            return $query->whereBetween('trip_details.created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                        })
                        ->when(isset($filter) && $filter == 'this_year', function ($query) {
                            return $query->whereYear('trip_details.created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'this_month', function ($query) {
                            return $query->whereMonth('trip_details.created_at', now()->format('m'))
                                ->whereYear('trip_details.created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                            return $query->whereYear('trip_details.created_at', date('Y') - 1);
                        })
                        ->when(isset($filter) && $filter == 'this_week', function ($query) {
                            return $query->whereBetween('trip_details.created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]); // Filter by this week
                        });
                },
            ])

            ->when($request->query('module_id', null), function ($query) use ($request) {
                return $query->module($request->query('module_id'));
            })
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->whereIn('provider_id', $zone->providers->pluck('id'));
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(isset($category), function ($query) use ($category) {
                return $query->where('category_id', $category->id);
            })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->with('module', 'provider')
            ->having('trips_count', '>' ,0)
            ->orderBy('trips_count', 'desc');

        return $items;
    }

    public function trip_transaction()
    {
        $trip_transactions = TripTransaction::latest()->paginate(config('default_pagination'));
        return view('rental::admin.report.trip-transactions', compact('trip_transactions'));
    }


    public function set_date(Request $request)
    {
        session()->put('from_date', date('Y-m-d', strtotime($request['from'])));
        session()->put('to_date', date('Y-m-d', strtotime($request['to'])));
        return back();
    }

    public function item_search(Request $request)
    {
        $key = explode(' ', $request['search']);

        if (session()->has('from_date') == false) {
            session()->put('from_date', now()->firstOfMonth()->format('Y-m-d'));
            session()->put('to_date', now()->lastOfMonth()->format('Y-m-d'));
        }
        $from = session('from_date');
        $to = session('to_date');

        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $provider_id = $request->query('provider_id', 'all');
        $category_id = $request->query('category_id', 'all');
        $filter = $request->query('filter', 'all_time');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $category = is_numeric($category_id) ? Category::findOrFail($category_id) : null;
        $items = \App\Models\Item::withoutGlobalScope(StoreScope::class)
            ->withCount([
                'trips' => function ($query) use ($from, $to, $filter) {
                    $query->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                        return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    })
                        ->when(isset($filter) && $filter == 'this_year', function ($query) {
                            return $query->whereYear('created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'this_month', function ($query) {
                            return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'this_month', function ($query) {
                            return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                            return $query->whereYear('created_at', date('Y') - 1);
                        })
                        ->when(isset($filter) && $filter == 'this_week', function ($query) {
                            return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                        })
                        ->when(isset($filter) && $filter == 'all_time', function ($query) {
                            return $query;
                        })
                        ->whereHas('trip', function ($query) {
                            return $query->whereIn('trip_status', ['delivered', 'refund_requested', 'refund_request_canceled']);
                        });
                },
            ])
            ->withSum([
                'trips' => function ($query) use ($from, $to, $filter) {
                    $query->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                        return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    })
                        ->when(isset($filter) && $filter == 'this_year', function ($query) {
                            return $query->whereYear('created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'this_month', function ($query) {
                            return $query->whereMonth('created_at', now()->format('m'));
                        })
                        ->when(isset($filter) && $filter == 'this_month', function ($query) {
                            return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                            return $query->whereYear('created_at', date('Y') - 1);
                        })
                        ->when(isset($filter) && $filter == 'this_week', function ($query) {
                            return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                        })
                        ->when(isset($filter) && $filter == 'all_time', function ($query) {
                            return $query;
                        })
                        ->whereHas('trip', function ($query) {
                            return $query->whereIn('trip_status', ['delivered', 'refund_requested', 'refund_request_canceled']);
                        });
                },
            ], 'discount_on_item')
            ->withSum([
                'trips' => function ($query) use ($from, $to, $filter) {
                    $query->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                        return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                    })
                        ->when(isset($filter) && $filter == 'this_year', function ($query) {
                            return $query->whereYear('created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'this_month', function ($query) {
                            return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'this_month', function ($query) {
                            return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                            return $query->whereYear('created_at', date('Y') - 1);
                        })
                        ->when(isset($filter) && $filter == 'this_week', function ($query) {
                            return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                        })
                        ->when(isset($filter) && $filter == 'all_time', function ($query) {
                            return $query;
                        })
                        ->whereHas('trip', function ($query) {
                            return $query->whereIn('trip_status', ['delivered', 'refund_requested', 'refund_request_canceled']);
                        });
                },
            ], 'price')
            ->when($request->query('module_id', null), function ($query) use ($request) {
                return $query->module($request->query('module_id'));
            })
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->whereIn('provider_id', $zone->providers->pluck('id'));
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(isset($category), function ($query) use ($category) {
                return $query->where('category_id', $category->id);
            })
            ->with('module', 'provider')
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            })
            ->limit(25)->get();

        return response()->json([
            'count' => count($items),
            'view' => view('rental::admin.report.partials._item_table', compact('items'))->render()
        ]);
    }

    public function stock_search(Request $request)
    {
        $key = explode(' ', $request['search']);

        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $provider_id = $request->query('provider_id', 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $stock_modules = array_keys(array_filter(config('module'), function ($var) {
            if (isset($var['stock']) && $var['stock']) return $var;
        }));
        $key = isset($request['search']) ? explode(' ', $request['search']) : [];

        $items = Item::withoutGlobalScope(StoreScope::class)->whereHas('provider.module', function ($query) use ($stock_modules) {
            $query->whereIn('module_type', $stock_modules);
        })
            ->when($request->query('module_id', null), function ($query) use ($request) {
                return $query->module($request->query('module_id'));
            })
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->whereIn('provider_id', $zone->providers->pluck('id'));
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(count($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->limit(25)->get();

        return response()->json([
            'count' => count($items),
            'view' => view('rental::admin.report.partials._stock_table', compact('items'))->render()
        ]);
    }

    public function provider_summary_report(Request $request)
    {
        $months = array(
            '"'.translate('Jan').'"',
            '"'.translate('Feb').'"',
            '"'.translate('Mar').'"',
            '"'.translate('Apr').'"',
            '"'.translate('May').'"',
            '"'.translate('Jun').'"',
            '"'.translate('Jul').'"',
            '"'.translate('Aug').'"',
            '"'.translate('Sep').'"',
            '"'.translate('Oct').'"',
            '"'.translate('Nov').'"',
            '"'.translate('Dec').'"'
        );
        $days = array(
            '"'.translate('Sun').'"',
            '"'.translate('Mon').'"',
            '"'.translate('Tue').'"',
            '"'.translate('Wed').'"',
            '"'.translate('Thu').'"',
            '"'.translate('Fri').'"',
            '"'.translate('Sat').'"'
        );

        $key = explode(' ', $request['search']);

        $filter = $request->query('filter', 'all_time');

        $providers = Store::with('trips')
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->with([
                    'trips' => function ($query) {
                        $query->StoreOrder()->whereYear('schedule_at', now()->format('Y'));
                    },
                ]);
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->with([
                    'trips' => function ($query) {
                        $query->StoreOrder()->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
                    },
                ]);
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->with([
                    'trips' => function ($query) {
                        $query->StoreOrder()->whereYear('schedule_at', date('Y') - 1);
                    },
                ]);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->with([
                    'trips' => function ($query) {
                        $query->StoreOrder()->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                    },
                ]);
            })
            ->when(isset($filter) && $filter == 'all_time', function ($query) {
                return $query->with([
                    'trips' => function ($query) {
                        $query->StoreOrder();
                    },
                ]);
            })
            ->orderBy('trip_count', 'DESC')->paginate(config('default_pagination'));

        $new_providers = Store::when(isset($filter) && $filter == 'this_year', function ($query) {
            return $query->whereYear('created_at', now()->format('Y'));
        })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })->count();

        $trip_payment_methods = Order::when(isset($filter) && $filter == 'this_year', function ($query) {
            return $query->whereYear('schedule_at', now()->format('Y'));
        })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->StoreOrder()->Delivered()->NotRefunded()
            ->selectRaw(DB::raw("sum(`trip_amount`) as total_trip_amount, count(*) as trip_count, IF((`payment_method`='cash_on_delivery'), `payment_method`, IF(`payment_method`='wallet',`payment_method`, 'digital_payment')) as 'payment_methods'"))->groupBy('payment_methods')
            ->get();

        $trips = Order::when(isset($filter) && $filter == 'this_year', function ($query) {
            return $query->whereYear('schedule_at', now()->format('Y'));
        })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })->StoreOrder()->get();
        $total_trip_amount = $trips->whereIn('trip_status', ['delivered'])->sum('trip_amount');
        $total_ongoing = $trips->whereIn('trip_status', ['pending', 'accepted', 'confirmed', 'processing', 'handover', 'picked_up'])->count();
        $total_canceled = $trips->whereIn('trip_status', ['failed', 'canceled'])->count();
        $total_delivered = $trips->whereIn('trip_status', ['delivered'])->count();

        $items = Item::when(isset($filter) && $filter == 'this_year', function ($query) {
            return $query->whereYear('created_at', now()->format('Y'));
        })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })->get();

        $monthly_trip = [];
        switch ($filter) {
            case "all_time":
                $monthly_trip = Order::select(
                    DB::raw("(sum(trip_amount)) as trip_amount"),
                    DB::raw("(DATE_FORMAT(schedule_at, '%Y')) as year")
                )
                    ->StoreOrder()->Delivered()->NotRefunded()
                    ->groupBy(DB::raw("DATE_FORMAT(schedule_at, '%Y')"))
                    ->get()->toArray();

                $label = array_map(function ($trip) {
                    return $trip['year'];
                }, $monthly_trip);
                $data = array_map(function ($trip) {
                    return $trip['trip_amount'];
                }, $monthly_trip);
                break;
            case "this_year":
                for ($i = 1; $i <= 12; $i++) {
                    $monthly_trip[$i] = Order::StoreOrder()->Delivered()->NotRefunded()->whereMonth('schedule_at', $i)->whereYear('schedule_at', now()->format('Y'))
                        ->sum('trip_amount');
                }
                $label = $months;
                $data = $monthly_trip;
                break;
            case "previous_year":
                for ($i = 1; $i <= 12; $i++) {
                    $monthly_trip[$i] = Order::StoreOrder()->Delivered()->NotRefunded()->whereMonth('schedule_at', $i)->whereYear('schedule_at', date('Y') - 1)
                        ->sum('trip_amount');
                }
                $label = $months;
                $data = $monthly_trip;
                break;
            case "this_week":
                $weekStartDate = now()->startOfWeek();
                for ($i = 1; $i <= 7; $i++) {
                    $monthly_trip[$i] = Order::StoreOrder()->Delivered()->NotRefunded()->whereDay('schedule_at', $weekStartDate->format('d'))->whereMonth('schedule_at', now()->format('m'))
                        ->sum('trip_amount');

                    $weekStartDate = $weekStartDate->addDays(1);
                }
                $label = $days;
                $data = $monthly_trip;
                break;
            case "this_month":
                $start = now()->startOfMonth();
                $end = now()->startOfMonth()->addDays(7);
                $total_day = now()->daysInMonth;
                $remaining_days = now()->daysInMonth - 28;
                $weeks = array(
                    '"'.translate('Day').' 1-7"',
                    '"'.translate('Day').' 8-14"',
                    '"'.translate('Day').' 15-21"',
                    '"'.translate('Day').' 22-' . $total_day . '"',
                );
                for ($i = 1; $i <= 4; $i++) {
                    $monthly_trip[$i] = Order::StoreOrder()->Delivered()->NotRefunded()
                        ->whereBetween('schedule_at', ["{$start->format('Y-m-d')} 00:00:00", "{$end->format('Y-m-d')} 23:59:59"])
                        ->sum('trip_amount');
                    $start = $start->addDays(7);
                    $end = $i == 3 ? $end->addDays(7 + $remaining_days) : $end->addDays(7);
                }
                $label = $weeks;
                $data = $monthly_trip;
                break;
            default:
                for ($i = 1; $i <= 12; $i++) {
                    $monthly_trip[$i] = Order::StoreOrder()->Delivered()->NotRefunded()->whereMonth('schedule_at', $i)->whereYear('schedule_at', now()->format('Y'))
                        ->sum('trip_amount');
                }
                $label = $months;
                $data = $monthly_trip;
        }

        return view('rental::admin.report.provider-summary-report', compact('providers', 'new_providers', 'trips', 'trip_payment_methods', 'items', 'monthly_trip', 'label', 'data', 'filter', 'total_trip_amount', 'total_ongoing', 'total_canceled', 'total_delivered'));
    }

    public function provider_summary_search(Request $request)
    {
        $key = explode(' ', $request['search']);

        $filter = $request->query('filter', 'all_time');

        $providers = Store::with('trips')
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->with([
                    'trips' => function ($query) {
                        $query->StoreOrder()->whereYear('schedule_at', now()->format('Y'));
                    },
                ]);
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->with([
                    'trips' => function ($query) {
                        $query->StoreOrder()->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
                    },
                ]);
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->with([
                    'trips' => function ($query) {
                        $query->StoreOrder()->whereYear('schedule_at', date('Y') - 1);
                    },
                ]);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->with([
                    'trips' => function ($query) {
                        $query->StoreOrder()->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                    },
                ]);
            })
            ->when(isset($filter) && $filter == 'all_time', function ($query) {
                return $query->with([
                    'trips' => function ($query) {
                        $query->StoreOrder();
                    },
                ]);
            })
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            })->Active()
            ->limit(25)->get();

        return response()->json([
            'count' => count($providers),
            'view' => view('rental::admin.report.partials._provider_summary_table', compact('providers'))->render()
        ]);
    }

    public function provider_sales_report(Request $request)
    {
        if (session()->has('from_date') == false) {
            session()->put('from_date', now()->firstOfMonth()->format('Y-m-d'));
            session()->put('to_date', now()->lastOfMonth()->format('Y-m-d'));
        }
        $from = session('from_date');
        $to = session('to_date');

        $months = array(
            '"'.translate('Jan').'"',
            '"'.translate('Feb').'"',
            '"'.translate('Mar').'"',
            '"'.translate('Apr').'"',
            '"'.translate('May').'"',
            '"'.translate('Jun').'"',
            '"'.translate('Jul').'"',
            '"'.translate('Aug').'"',
            '"'.translate('Sep').'"',
            '"'.translate('Oct').'"',
            '"'.translate('Nov').'"',
            '"'.translate('Dec').'"'
        );
        $days = array(
            '"'.translate('Sun').'"',
            '"'.translate('Mon').'"',
            '"'.translate('Tue').'"',
            '"'.translate('Wed').'"',
            '"'.translate('Thu').'"',
            '"'.translate('Fri').'"',
            '"'.translate('Sat').'"'
        );
        $key = isset($request['search']) ? explode(' ', $request['search']) : [];
        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $provider_id = $request->query('provider_id', 'all');
        $filter = $request->query('filter', 'all_time');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;

        // items


        $items=$this->get_provider_sales_data($request)['items'];
        $items= $items->paginate(config('default_pagination'))->withQueryString();
        $trips=$this->get_provider_sales_data($request)['trips'];

        // custom filtering for bar chart
        $monthly_trip = [];
        $label = [];
        if ($filter != 'custom') {
            switch ($filter) {
                case "all_time":
                    $monthly_trip = Order::StoreOrder()->Delivered()->NotRefunded()->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                    })
                        ->when(isset($provider), function ($query) use ($provider) {
                            return $query->where('provider_id', $provider->id);
                        })->select(
                            DB::raw("(sum(trip_amount)) as trip_amount"),
                            DB::raw("(DATE_FORMAT(schedule_at, '%Y')) as year")
                        )
                        ->groupBy(DB::raw("DATE_FORMAT(schedule_at, '%Y')"))
                        ->get()->toArray();

                    $label = array_map(function ($trip) {
                        return $trip['year'];
                    }, $monthly_trip);
                    $data = array_map(function ($trip) {
                        return $trip['trip_amount'];
                    }, $monthly_trip);
                    break;
                case "this_year":
                    for ($i = 1; $i <= 12; $i++) {
                        $monthly_trip[$i] = Order::StoreOrder()->Delivered()->NotRefunded()->when(isset($zone), function ($query) use ($zone) {
                            return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                        })
                            ->when(isset($provider), function ($query) use ($provider) {
                                return $query->where('provider_id', $provider->id);
                            })->whereMonth('schedule_at', $i)->whereYear('schedule_at', now()->format('Y'))
                            ->sum('trip_amount');
                    }
                    $label = $months;
                    $data = $monthly_trip;
                    break;
                case "previous_year":
                    for ($i = 1; $i <= 12; $i++) {
                        $monthly_trip[$i] = Order::StoreOrder()->Delivered()->NotRefunded()->when(isset($zone), function ($query) use ($zone) {
                            return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                        })
                            ->when(isset($provider), function ($query) use ($provider) {
                                return $query->where('provider_id', $provider->id);
                            })->whereMonth('schedule_at', $i)->whereYear('schedule_at', date('Y') - 1)
                            ->sum('trip_amount');
                    }
                    $label = $months;
                    $data = $monthly_trip;
                    break;
                case "this_week":
                    $weekStartDate = now()->startOfWeek();
                    for ($i = 1; $i <= 7; $i++) {
                        $monthly_trip[$i] = Order::StoreOrder()->Delivered()->NotRefunded()->when(isset($zone), function ($query) use ($zone) {
                            return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                        })
                            ->when(isset($provider), function ($query) use ($provider) {
                                return $query->where('provider_id', $provider->id);
                            })->whereDay('schedule_at', $weekStartDate->format('d'))->whereMonth('schedule_at', now()->format('m'))
                            ->sum('trip_amount');
                        $weekStartDate = $weekStartDate->addDays(1);
                    }
                    $label = $days;
                    $data = $monthly_trip;
                    break;
                case "this_month":
                    $start = now()->startOfMonth();
                    $end = now()->startOfMonth()->addDays(6);
                    $total_day = now()->daysInMonth;
                    $remaining_days = now()->daysInMonth - 28;
                    $weeks = array(
                        '"'.translate('Day').' 1-7"',
                        '"'.translate('Day').' 8-14"',
                        '"'.translate('Day').' 15-21"',
                        '"'.translate('Day').' 22-' . $total_day . '"',
                    );
                    for ($i = 1; $i <= 4; $i++) {
                        $monthly_trip[$i] = Order::StoreOrder()->Delivered()->NotRefunded()->when(isset($zone), function ($query) use ($zone) {
                            return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                        })
                            ->when(isset($provider), function ($query) use ($provider) {
                                return $query->where('provider_id', $provider->id);
                            })
                            ->whereBetween('schedule_at', ["{$start->format('Y-m-d')} 00:00:00", "{$end->format('Y-m-d')} 23:59:59"])
                            ->sum('trip_amount');
                        $start = $start->addDays(7);
                        $end = $i == 3 ? $end->addDays(7 + $remaining_days) : $end->addDays(7);
                    }
                    $label = $weeks;
                    $data = $monthly_trip;
                    break;
                default:
                    for ($i = 1; $i <= 12; $i++) {
                        $monthly_trip[$i] = Order::StoreOrder()->Delivered()->NotRefunded()->when(isset($zone), function ($query) use ($zone) {
                            return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                        })
                            ->when(isset($provider), function ($query) use ($provider) {
                                return $query->where('provider_id', $provider->id);
                            })->whereMonth('schedule_at', $i)->whereYear('schedule_at', now()->format('Y'))
                            ->sum('trip_amount');
                    }
                    $label = $months;
                    $data = $monthly_trip;
            }
        } else {

            $to = Carbon::parse($to);
            $from = Carbon::parse($from);

            $years_count = $to->diffInYears($from);
            $months_count = $to->diffInMonths($from);
            $weeks_count = $to->diffInWeeks($from);
            $days_count = $to->diffInDays($from);


            if ($years_count > 0) {
                $monthly_trip = Order::StoreOrder()->Delivered()->NotRefunded()->when(isset($zone), function ($query) use ($zone) {
                    return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                })
                    ->when(isset($provider), function ($query) use ($provider) {
                        return $query->where('provider_id', $provider->id);
                    })
                    ->whereBetween('schedule_at', ["{$from}", "{$to->format('Y-m-d')} 23:59:59"])
                    ->select(
                        DB::raw("(sum(trip_amount)) as trip_amount"),
                        DB::raw("(DATE_FORMAT(schedule_at, '%Y')) as year")
                    )
                    ->groupBy('year')
                    ->get()->toArray();

                $label = array_map(function ($trip) {
                    return $trip['year'];
                }, $monthly_trip);
                $data = array_map(function ($trip) {
                    return $trip['trip_amount'];
                }, $monthly_trip);
            } elseif ($months_count > 0) {
                for ($i = (int)$from->format('m'); $i <= (int)$from->format('m') + $months_count; $i++) {
                    $monthly_trip[$i] = Order::StoreOrder()->Delivered()->NotRefunded()->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                    })
                        ->when(isset($provider), function ($query) use ($provider) {
                            return $query->where('provider_id', $provider->id);
                        })->whereMonth('schedule_at', $i)
                        ->sum('trip_amount');
                    $label[$i] = $months[$i - 1];
                }
                $label = $label;
                $data = $monthly_trip;
            } elseif ($weeks_count > 0) {
                for ($i = (int)$from->format('d'); $i <= (int)$to->format('d'); $i++) {
                    $monthly_trip[$i] = Order::StoreOrder()->Delivered()->NotRefunded()->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                    })
                        ->when(isset($provider), function ($query) use ($provider) {
                            return $query->where('provider_id', $provider->id);
                        })->whereDay('schedule_at', $i)->whereMonth('schedule_at', $from->format('m'))->whereYear('schedule_at', $from->format('Y'))
                        ->sum('trip_amount');
                    $label[$i] = $i;
                }
                $label = $label;
                $data = $monthly_trip;
            } elseif ($days_count >= 0) {
                for ($i = (int)$from->format('d'); $i <= (int)$to->format('d'); $i++) {
                    $monthly_trip[$i] = Order::StoreOrder()->Delivered()->NotRefunded()->when(isset($zone), function ($query) use ($zone) {
                        return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                    })
                        ->when(isset($provider), function ($query) use ($provider) {
                            return $query->where('provider_id', $provider->id);
                        })->whereDay('schedule_at', $i)->whereMonth('schedule_at', $from->format('m'))->whereYear('schedule_at', $from->format('Y'))
                        ->sum('trip_amount');
                    $label[$i] = $i;
                }
                $label = $label;
                $data = $monthly_trip;
            }
        }

        return view('rental::admin.report.provider-sales-report', compact('zone', 'provider', 'items', 'trips', 'data', 'label', 'filter'));
    }


    public function provider_sales_export(Request $request)
    {
        $from = session('from_date');
        $to = session('to_date');
        $filter = $request->query('filter', 'all_time');

        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $provider_id = $request->query('provider_id', 'all');


        $items=$this->get_provider_sales_data($request)['items'];
        $items= $items->get();
        $trips=$this->get_provider_sales_data($request)['trips'];

        $data = [
            'items'=>$items,
            'trips'=>$trips,
            'search'=>$request->search??null,
            'from'=>(($filter == 'custom') && $from)?$from:null,
            'to'=>(($filter == 'custom') && $to)?$to:null,
            'zone'=>is_numeric($zone_id)?Helpers::get_zones_name($zone_id):null,
            'provider'=>is_numeric($provider_id)?Helpers::get_providers_name($provider_id):null,
            'filter'=>$filter,
        ];
        if ($request->type == 'excel') {
            return Excel::download(new StoreSalesReportExport($data), 'StoreSalesReport.xlsx');
        } else if ($request->type == 'csv') {
            return Excel::download(new StoreSalesReportExport($data), 'StoreSalesReport.csv');
        }
    }


    private static function get_provider_sales_data($request){
        if (session()->has('from_date') == false) {
            session()->put('from_date', now()->firstOfMonth()->format('Y-m-d'));
            session()->put('to_date', now()->lastOfMonth()->format('Y-m-d'));
        }
        $from = session('from_date');
        $to = session('to_date');
        $key = isset($request['search']) ? explode(' ', $request['search']) : [];
        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $provider_id = $request->query('provider_id', 'all');
        $filter = $request->query('filter', 'all_time');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;

        $items =   Item::withoutGlobalScope(StoreScope::class)
            ->withCount([
                'trips' => function ($query) use ($from, $to, $filter) {
                    $query->whereHas('trip', function ($query) {
                        return $query->whereIn('trip_status', ['delivered', 'refund_requested', 'refund_request_canceled']);
                    })->applyDateFilter($filter, $from, $to);
                },
            ])
            ->withSum([
                'trips' => function ($query) use ($from, $to, $filter) {
                    $query->whereHas('trip', function ($query) {
                        return $query->whereIn('trip_status', ['delivered', 'refund_requested', 'refund_request_canceled']);
                    })->applyDateFilter($filter, $from, $to);
                },
            ], 'quantity')

            ->addSelect([
                'total_discount' => function ($query) use ($from, $to, $filter) {
                    $query->selectRaw('SUM(trip_details.discount_on_item * trip_details.quantity)')
                        ->from('trip_details')
                        ->join('trips', 'trips.id', '=', 'trip_details.trip_id')
                        ->whereColumn('trip_details.item_id', 'items.id')
                        ->whereIn('trips.trip_status', ['delivered', 'refund_requested', 'refund_request_canceled'])
                        ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                            return $query->whereBetween('trip_details.created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                        })
                        ->when(isset($filter) && $filter == 'this_year', function ($query) {
                            return $query->whereYear('trip_details.created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'this_month', function ($query) {
                            return $query->whereMonth('trip_details.created_at', now()->format('m'))
                                ->whereYear('trip_details.created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                            return $query->whereYear('trip_details.created_at', date('Y') - 1);
                        })
                        ->when(isset($filter) && $filter == 'this_week', function ($query) {
                            return $query->whereBetween('trip_details.created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]); // Filter by this week
                        });
                },
                'trips_sum_price' => function ($query) use ($from, $to, $filter) {
                    $query->selectRaw('SUM(trip_details.price * trip_details.quantity)')
                        ->from('trip_details')
                        ->join('trips', 'trips.id', '=', 'trip_details.trip_id')
                        ->whereColumn('trip_details.item_id', 'items.id')
                        ->whereIn('trips.trip_status', ['delivered', 'refund_requested', 'refund_request_canceled'])
                        ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                            return $query->whereBetween('trip_details.created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
                        })
                        ->when(isset($filter) && $filter == 'this_year', function ($query) {
                            return $query->whereYear('trip_details.created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'this_month', function ($query) {
                            return $query->whereMonth('trip_details.created_at', now()->format('m'))
                                ->whereYear('trip_details.created_at', now()->format('Y'));
                        })
                        ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                            return $query->whereYear('trip_details.created_at', date('Y') - 1);
                        })
                        ->when(isset($filter) && $filter == 'this_week', function ($query) {
                            return $query->whereBetween('trip_details.created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]); // Filter by this week
                        });
                },
            ])
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->whereIn('provider_id', $zone->providers->pluck('id'));
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->having('trips_count', '>' ,0)
            ->orderBy('trips_count', 'desc');

        $trips = Order::StoreOrder()
            ->whereNotIn('trip_status', ['refunded', 'failed', 'canceled'])
            ->Delivered()->with('transaction')->when(isset($zone), function ($query) use ($zone) {
                return $query->whereIn('provider_id', $zone->providers->pluck('id'));
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->withSum('transaction', 'admin_commission')
            ->withSum('transaction', 'admin_expense')
            ->withSum('transaction', 'delivery_fee_comission')
            ->withSum('transaction', 'provider_amount')
            ->get();

        return ['items'=> $items , 'trips'=> $trips];
    }







    public function provider_trip_report(Request $request)
    {
        if (session()->has('from_date') == false) {
            session()->put('from_date', now()->firstOfMonth()->format('Y-m-d'));
            session()->put('to_date', now()->lastOfMonth()->format('Y-m-d'));
        }
        $from = session('from_date');
        $to = session('to_date');

        $months = array(
            '"'.translate('Jan').'"',
            '"'.translate('Feb').'"',
            '"'.translate('Mar').'"',
            '"'.translate('Apr').'"',
            '"'.translate('May').'"',
            '"'.translate('Jun').'"',
            '"'.translate('Jul').'"',
            '"'.translate('Aug').'"',
            '"'.translate('Sep').'"',
            '"'.translate('Oct').'"',
            '"'.translate('Nov').'"',
            '"'.translate('Dec').'"'
        );
        $days = array(
            '"'.translate('Sun').'"',
            '"'.translate('Mon').'"',
            '"'.translate('Tue').'"',
            '"'.translate('Wed').'"',
            '"'.translate('Thu').'"',
            '"'.translate('Fri').'"',
            '"'.translate('Sat').'"'
        );

        $key = isset($request['search']) ? explode(' ', $request['search']) : [];

        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $provider_id = $request->query('provider_id', 'all');
        $filter = $request->query('filter', 'all_time');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;

        // trip list with pagination
        $trips = Order::with(['customer', 'provider'])
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%");
                    }
                });
            })
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->whereIn('provider_id', $zone->providers->pluck('id'));
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->StoreOrder()->NotRefunded()
            ->withSum('transaction', 'admin_commission')
            ->withSum('transaction', 'admin_expense')
            ->withSum('transaction', 'delivery_fee_comission')
            ->orderBy('schedule_at', 'desc')->paginate(config('default_pagination'));

        // trip card values calculation
        $trips_list = Order::with(['customer', 'provider'])
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->whereIn('provider_id', $zone->providers->pluck('id'));
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->StoreOrder()->NotRefunded()
            ->withSum('transaction', 'admin_commission')
            ->withSum('transaction', 'admin_expense')
            ->withSum('transaction', 'delivery_fee_comission')
            ->orderBy('schedule_at', 'desc')->get();

        $total_trip_amount = $trips_list->sum('trip_amount');
        $total_coupon_discount = $trips_list->sum('coupon_discount_amount');
        $total_product_discount = $trips_list->sum('provider_discount_amount');

        $total_ongoing = $trips_list->whereIn('trip_status', ['pending', 'accepted', 'confirmed', 'processing', 'handover', 'picked_up'])->sum('trip_amount');
        $total_canceled = $trips_list->whereIn('trip_status', ['failed', 'canceled'])->sum('trip_amount');
        $total_delivered = $trips_list->where('trip_status', 'delivered')->sum('trip_amount');
        $total_ongoing_count = $trips_list->whereIn('trip_status', ['pending', 'accepted', 'confirmed', 'processing', 'handover', 'picked_up'])->count();
        $total_canceled_count = $trips_list->whereIn('trip_status', ['failed', 'canceled'])->count();
        $total_delivered_count = $trips_list->where('trip_status', 'delivered')->count();

        // payment type statistics
        $trip_payment_methods = Order::when(isset($zone), function ($query) use ($zone) {
            return $query->whereIn('provider_id', $zone->providers->pluck('id'));
        })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->StoreOrder()->NotRefunded()
            ->selectRaw(DB::raw("sum(`trip_amount`) as total_trip_amount, count(*) as trip_count, IF((`payment_method`='cash_on_delivery'), `payment_method`, IF(`payment_method`='wallet',`payment_method`, 'digital_payment')) as 'payment_methods'"))
            ->groupBy('payment_methods')
            ->get();

        // custom filtering for bar chart
        $monthly_trip = [];
        $label = [];
        if ($filter != 'custom') {
            switch ($filter) {
                case "all_time":
                    $monthly_trip = Order::when(isset($zone), function ($query) use ($zone) {
                        return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                    })
                        ->when(isset($provider), function ($query) use ($provider) {
                            return $query->where('provider_id', $provider->id);
                        })
                        ->StoreOrder()->NotRefunded()
                        ->select(
                            DB::raw("(sum(trip_amount)) as trip_amount"),
                            DB::raw("(DATE_FORMAT(schedule_at, '%Y')) as year")
                        )
                        ->groupBy(DB::raw("DATE_FORMAT(schedule_at, '%Y')"))
                        ->get()->toArray();

                    $label = array_map(function ($trip) {
                        return $trip['year'];
                    }, $monthly_trip);
                    $data = array_map(function ($trip) {
                        return $trip['trip_amount'];
                    }, $monthly_trip);
                    break;
                case "this_year":
                    for ($i = 1; $i <= 12; $i++) {
                        $monthly_trip[$i] = Order::when(isset($zone), function ($query) use ($zone) {
                            return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                        })
                            ->when(isset($provider), function ($query) use ($provider) {
                                return $query->where('provider_id', $provider->id);
                            })
                            ->StoreOrder()->NotRefunded()
                            ->whereMonth('schedule_at', $i)->whereYear('schedule_at', now()->format('Y'))
                            ->sum('trip_amount');
                    }
                    $label = $months;
                    $data = $monthly_trip;
                    break;
                case "previous_year":
                    for ($i = 1; $i <= 12; $i++) {
                        $monthly_trip[$i] = Order::when(isset($zone), function ($query) use ($zone) {
                            return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                        })
                            ->when(isset($provider), function ($query) use ($provider) {
                                return $query->where('provider_id', $provider->id);
                            })
                            ->StoreOrder()->NotRefunded()
                            ->whereMonth('schedule_at', $i)->whereYear('schedule_at', date('Y') - 1)
                            ->sum('trip_amount');
                    }
                    $label = $months;
                    $data = $monthly_trip;
                    break;
                case "this_week":
                    $weekStartDate = now()->startOfWeek();
                    for ($i = 1; $i <= 7; $i++) {
                        $monthly_trip[$i] = Order::when(isset($zone), function ($query) use ($zone) {
                            return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                        })
                            ->when(isset($provider), function ($query) use ($provider) {
                                return $query->where('provider_id', $provider->id);
                            })->StoreOrder()->NotRefunded()->whereDay('schedule_at', $weekStartDate->format('d'))->whereMonth('schedule_at', now()->format('m'))
                            ->sum('trip_amount');
                        $weekStartDate = $weekStartDate->addDays(1);
                    }
                    $label = $days;
                    $data = $monthly_trip;
                    break;
                case "this_month":
                    $start = now()->startOfMonth();
                    $end = now()->startOfMonth()->addDays(7);
                    $total_day = now()->daysInMonth;
                    $remaining_days = now()->daysInMonth - 28;
                    $weeks = array(
                        '"'.translate('Day').' 1-7"',
                        '"'.translate('Day').' 8-14"',
                        '"'.translate('Day').' 15-21"',
                        '"'.translate('Day').' 22-' . $total_day . '"',
                    );
                    for ($i = 1; $i <= 4; $i++) {
                        $monthly_trip[$i] = Order::when(isset($zone), function ($query) use ($zone) {
                            return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                        })
                            ->when(isset($provider), function ($query) use ($provider) {
                                return $query->where('provider_id', $provider->id);
                            })
                            ->StoreOrder()->NotRefunded()
                            ->whereBetween('schedule_at', ["{$start->format('Y-m-d')} 00:00:00", "{$end->format('Y-m-d')} 23:59:59"])
                            ->sum('trip_amount');
                        $start = $start->addDays(7);
                        $end = $i == 3 ? $end->addDays(7 + $remaining_days) : $end->addDays(7);
                    }
                    $label = $weeks;
                    $data = $monthly_trip;
                    break;
                default:
                    for ($i = 1; $i <= 12; $i++) {
                        $monthly_trip[$i] = Order::when(isset($zone), function ($query) use ($zone) {
                            return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                        })
                            ->when(isset($provider), function ($query) use ($provider) {
                                return $query->where('provider_id', $provider->id);
                            })->StoreOrder()->NotRefunded()->whereMonth('schedule_at', $i)->whereYear('schedule_at', now()->format('Y'))
                            ->sum('trip_amount');
                    }
                    $label = $months;
                    $data = $monthly_trip;
            }
        } else {

            $to = Carbon::parse($to);
            $from = Carbon::parse($from);

            $years_count = $to->diffInYears($from);
            $months_count = $to->diffInMonths($from);
            $weeks_count = $to->diffInWeeks($from);
            $days_count = $to->diffInDays($from);

            // dd($days_count);


            if ($years_count > 0) {
                $monthly_trip = Order::when(isset($zone), function ($query) use ($zone) {
                    return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                })
                    ->when(isset($provider), function ($query) use ($provider) {
                        return $query->where('provider_id', $provider->id);
                    })
                    ->StoreOrder()->NotRefunded()
                    ->whereBetween('schedule_at', ["{$from}", "{$to->format('Y-m-d')} 23:59:59"])
                    ->select(
                        DB::raw("(sum(trip_amount)) as trip_amount"),
                        DB::raw("(DATE_FORMAT(schedule_at, '%Y')) as year")
                    )
                    ->groupBy('year')
                    ->get()->toArray();

                $label = array_map(function ($trip) {
                    return $trip['year'];
                }, $monthly_trip);
                $data = array_map(function ($trip) {
                    return $trip['trip_amount'];
                }, $monthly_trip);
            } elseif ($months_count > 0) {
                for ($i = (int)$from->format('m'); $i <= (int)$from->format('m') + $months_count; $i++) {
                    $monthly_trip[$i] = Order::when(isset($zone), function ($query) use ($zone) {
                        return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                    })
                        ->when(isset($provider), function ($query) use ($provider) {
                            return $query->where('provider_id', $provider->id);
                        })
                        ->StoreOrder()->NotRefunded()
                        ->whereMonth('schedule_at', $i)
                        ->sum('trip_amount');
                    $label[$i] = $months[$i - 1];
                }
                $label = $label;
                $data = $monthly_trip;
            } elseif ($weeks_count > 0) {
                // $start = $from;
                // $end = $from->addDays(7);
                // $weeks = [];
                // for ($i = 1; $i <= 4; $i++) {
                //     $weeks[$i] = '"'.translate('Day').' ' . (int)$start->format('d') . '-' . ((int)$start->format('d') + 7) . '"';
                //     $monthly_trip[$i] = Order::when(isset($zone), function ($query) use ($zone) {
                //         return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                //     })
                //         ->when(isset($provider), function ($query) use ($provider) {
                //             return $query->where('provider_id', $provider->id);
                //         })
                //         ->whereBetween('schedule_at', [$start, "{$end->format('Y-m-d')} 23:59:59"])
                //         ->sum('trip_amount');

                //     $start = $end;
                //     $end = $start->addDays(7);
                // }
                // $label = $weeks;
                // $data = $monthly_trip;
                for ($i = (int)$from->format('d'); $i <= (int)$to->format('d'); $i++) {
                    $monthly_trip[$i] = Order::when(isset($zone), function ($query) use ($zone) {
                        return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                    })
                        ->when(isset($provider), function ($query) use ($provider) {
                            return $query->where('provider_id', $provider->id);
                        })
                        ->StoreOrder()->NotRefunded()
                        ->whereDay('schedule_at', $i)->whereMonth('schedule_at', $from->format('m'))->whereYear('schedule_at', $from->format('Y'))
                        ->sum('trip_amount');
                    $label[$i] = $i;
                }
                $label = $label;
                $data = $monthly_trip;
            } elseif ($days_count >= 0) {
                for ($i = (int)$from->format('d'); $i <= (int)$to->format('d'); $i++) {
                    $monthly_trip[$i] = Order::when(isset($zone), function ($query) use ($zone) {
                        return $query->whereIn('provider_id', $zone->providers->pluck('id'));
                    })
                        ->when(isset($provider), function ($query) use ($provider) {
                            return $query->where('provider_id', $provider->id);
                        })
                        ->StoreOrder()->NotRefunded()
                        ->whereDay('schedule_at', $i)->whereMonth('schedule_at', $from->format('m'))->whereYear('schedule_at', $from->format('Y'))
                        ->sum('trip_amount');
                    $label[$i] = $i;
                }
                $label = $label;
                $data = $monthly_trip;
            }
        }


        return view('rental::admin.report.provider-trip-report', compact('zone', 'provider', 'trips', 'trips_list', 'monthly_trip', 'total_trip_amount', 'trip_payment_methods', 'total_coupon_discount', 'total_product_discount', 'label', 'data', 'filter', 'total_ongoing', 'total_canceled', 'total_delivered', 'total_ongoing_count', 'total_canceled_count', 'total_delivered_count'));
    }

    public function provider_trip_search(Request $request)
    {
        $key = explode(' ', $request['search']);

        $from = session('from_date');
        $to = session('to_date');

        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $provider_id = $request->query('provider_id', 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;

        $trips = Order::with(['customer', 'provider'])
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->whereIn('provider_id', $zone->providers->pluck('id'));
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null, function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%");
                }
            })
            ->withSum('transaction', 'admin_commission')
            ->withSum('transaction', 'admin_expense')
            ->withSum('transaction', 'delivery_fee_comission')
            ->StoreOrder()->NotRefunded()
            ->orderBy('schedule_at', 'desc')
            ->limit(25)->get();

        return response()->json([
            'count' => count($trips),
            'view' => view('rental::admin.report.partials._provider_trip_table', compact('trips'))->render()
        ]);
    }

    public function provider_trip_export(Request $request)
    {
        $key = isset($request['search']) ? explode(' ', $request['search']) : [];

        $from = session('from_date');
        $to = session('to_date');

        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $provider_id = $request->query('provider_id', 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $filter = $request->query('filter', 'all_time');

        $trips = Order::with(['customer', 'provider'])
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%");
                    }
                });
            })
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->whereIn('provider_id', $zone->providers->pluck('id'));
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->StoreOrder()->NotRefunded()
            ->withSum('transaction', 'admin_commission')
            ->withSum('transaction', 'admin_expense')
            ->withSum('transaction', 'delivery_fee_comission')
            ->orderBy('schedule_at', 'desc')->get();

        $trips_list = Order::with(['customer', 'provider'])
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->whereIn('provider_id', $zone->providers->pluck('id'));
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->StoreOrder()->NotRefunded()
            ->withSum('transaction', 'admin_commission')
            ->withSum('transaction', 'admin_expense')
            ->withSum('transaction', 'delivery_fee_comission')
            ->orderBy('schedule_at', 'desc')->get();

        $total_trip_amount = $trips_list->sum('trip_amount');
        $total_coupon_discount = $trips_list->sum('coupon_discount_amount');
        $total_product_discount = $trips_list->sum('provider_discount_amount');

        $total_ongoing = $trips_list->whereIn('trip_status', ['pending', 'accepted', 'confirmed', 'processing', 'handover', 'picked_up'])->sum('trip_amount');
        $total_canceled = $trips_list->whereIn('trip_status', ['failed', 'canceled'])->sum('trip_amount');
        $total_delivered = $trips_list->where('trip_status', 'delivered')->sum('trip_amount');
        $total_ongoing_count = $trips_list->whereIn('trip_status', ['pending', 'accepted', 'confirmed', 'processing', 'handover', 'picked_up'])->count();
        $total_canceled_count = $trips_list->whereIn('trip_status', ['failed', 'canceled'])->count();
        $total_delivered_count = $trips_list->where('trip_status', 'delivered')->count();


        $data = [
            'trips'=>$trips,
            'total_trips'=>$trips->count(),
            'total_trip_amount'=>$total_trip_amount,
            'total_ongoing_count'=>$total_ongoing_count,
            'total_canceled_count'=>$total_canceled_count,
            'total_delivered_count'=>$total_delivered_count,
            'search'=>$request->search??null,
            'from'=>(($filter == 'custom') && $from)?$from:null,
            'to'=>(($filter == 'custom') && $to)?$to:null,
            'zone'=>is_numeric($zone_id)?Helpers::get_zones_name($zone_id):null,
            'provider'=>is_numeric($provider_id)?Helpers::get_providers_name($provider_id):null,
            'filter'=>$filter,
        ];
        if ($request->type == 'excel') {
            return Excel::download(new StoreOrderReportExport($data), 'StoreOrderReport.xlsx');
        } else if ($request->type == 'csv') {
            return Excel::download(new StoreOrderReportExport($data), 'StoreOrderReport.csv');
        }

        if ($request->type == 'excel') {
            return (new FastExcel(OrderLogic::format_provider_trip_export_data($trips)))->download('Orders.xlsx');
        } elseif ($request->type == 'csv') {
            return (new FastExcel(OrderLogic::format_provider_trip_export_data($trips)))->download('Orders.csv');
        }
    }



    public function provider_summary_export(Request $request)
    {
        $key = isset($request['search']) ? explode(' ', $request['search']) : [];

        $filter = $request->query('filter', 'all_time');

        $providers = Store::with('trips')
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->with([
                    'trips' => function ($query) {
                        $query->StoreOrder()->whereYear('schedule_at', now()->format('Y'));
                    },
                ]);
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->with([
                    'trips' => function ($query) {
                        $query->StoreOrder()->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
                    },
                ]);
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->with([
                    'trips' => function ($query) {
                        $query->StoreOrder()->whereYear('schedule_at', date('Y') - 1);
                    },
                ]);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->with([
                    'trips' => function ($query) {
                        $query->StoreOrder()->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
                    },
                ]);
            })
            ->when(isset($filter) && $filter == 'all_time', function ($query) {
                return $query->with([
                    'trips' => function ($query) {
                        $query->StoreOrder();
                    },
                ]);
            })
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            })
            ->Active()->orderBy('trip_count', 'DESC')->get();
        $trip_payment_methods = Order::when(isset($filter) && $filter == 'this_year', function ($query) {
            return $query->whereYear('schedule_at', now()->format('Y'));
        })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->StoreOrder()->Delivered()->NotRefunded()
            ->selectRaw(DB::raw("sum(`trip_amount`) as total_trip_amount, count(*) as trip_count, IF((`payment_method`='cash_on_delivery'), `payment_method`, IF(`payment_method`='wallet',`payment_method`, 'digital_payment')) as 'payment_methods'"))->groupBy('payment_methods')
            ->get();

        $new_providers = Store::when(isset($filter) && $filter == 'this_year', function ($query) {
            return $query->whereYear('created_at', now()->format('Y'));
        })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })->count();
        $trips = Order::when(isset($filter) && $filter == 'this_year', function ($query) {
            return $query->whereYear('schedule_at', now()->format('Y'));
        })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })->StoreOrder()->get();
        $total_trip_amount = $trips->whereIn('trip_status', ['delivered'])->sum('trip_amount');
        $total_ongoing = $trips->whereIn('trip_status', ['pending', 'accepted', 'confirmed', 'processing', 'handover', 'picked_up'])->count();
        $total_canceled = $trips->whereIn('trip_status', ['failed', 'canceled'])->count();
        $total_delivered = $trips->whereIn('trip_status', ['delivered'])->count();

        $data = [
            'providers'=>$providers,
            'search'=>$request->search??null,
            'new_providers'=>$new_providers,
            'trips'=>$trips->count(),
            'total_trip_amount'=>$total_trip_amount,
            'total_ongoing'=>$total_ongoing,
            'total_canceled'=>$total_canceled,
            'total_delivered'=>$total_delivered,
            'cash_payments'=>count($trip_payment_methods)>0?\App\CentralLogics\Helpers::number_format_short(isset($trip_payment_methods[0])?$trip_payment_methods[0]->total_trip_amount:0):0,
            'digital_payments'=>count($trip_payment_methods)>0?\App\CentralLogics\Helpers::number_format_short(isset($trip_payment_methods[1])?$trip_payment_methods[1]->total_trip_amount:0):0,
            'wallet_payments'=>count($trip_payment_methods)>0?\App\CentralLogics\Helpers::number_format_short(isset($trip_payment_methods[2])?$trip_payment_methods[2]->total_trip_amount:0):0,
            'filter'=>$filter,
        ];
        if ($request->type == 'excel') {
            return Excel::download(new StoreSummaryReportExport($data), 'StoreSummaryReport.xlsx');
        } else if ($request->type == 'csv') {
            return Excel::download(new StoreSummaryReportExport($data), 'StoreSummaryReport.csv');
        }
    }

    public function expense_export(Request $request)
    {
        $key = explode(' ', $request['search']);
        if (session()->has('from_date') == false) {
            session()->put('from_date', now()->firstOfMonth()->format('Y-m-d'));
            session()->put('to_date', now()->lastOfMonth()->format('Y-m-d'));
        }
        $from = session('from_date');
        $to = session('to_date');
        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider_id = $request->query('provider_id', 'all');
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
        $filter = $request->query('filter', 'all_time');
        $module = request()->module;
        $type = $request->query('type', 'all');

        $expense = Expense::with('trip', 'trip.customer:id,f_name,l_name')->where('created_by', 'admin')->where('amount', '>' ,0)
            ->when($zone || $module || $customer || $provider, function ($query) use ($zone, $module, $customer, $provider) {
                $query->whereHas('trip', function ($query) use ($zone, $provider, $customer, $module) {
                    $query->when($module, function ($query) use ($module) {
                        return $query->module($module);
                    });
                    $query->when($zone, function ($query) use ($zone) {
                        return $query->where('zone_id', $zone->id);
                    });
                    $query->when($provider, function ($query) use ($provider) {
                        return $query->where('provider_id', $provider->id);
                    });
                    $query->when($customer, function ($query) use ($customer) {
                        return $query->where('user_id', $customer->id);
                    });
                });
            })
            ->when(isset($type) &&  $type != 'all', function ($query) use ($type) {
                return $query->where('type',$type);
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->when(isset($key), function ($query) use ($key){
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('type', 'like', "%{$value}%")->orWhere('trip_id', 'like', "%{$value}%");
                    }
                });
            })
            ->orderBy('id')->get();

        $data = [
            'expenses'=>$expense,
            'search'=>$request->search??null,
            'from'=>(($filter == 'custom') && $from)?$from:null,
            'to'=>(($filter == 'custom') && $to)?$to:null,
            'zone'=>is_numeric($zone_id)?Helpers::get_zones_name($zone_id):null,
            'provider'=>is_numeric($provider_id)?Helpers::get_providers_name($provider_id):null,
            'customer'=>is_numeric($customer_id)?Helpers::get_customer_name($customer_id):null,
            'module'=>request('module_id')?Helpers::get_module_name(request('module_id')):null,
            'filter'=>$filter,
        ];

        if ($request->export_type == 'excel') {
            return Excel::download(new ExpenseReportExport($data), 'ExpenseReport.xlsx');
        } else if ($request->export_type == 'csv') {
            return Excel::download(new ExpenseReportExport($data), 'ExpenseReport.csv');
        }
    }

    public function expense_search(Request $request)
    {
        $key = explode(' ', $request['search']);

        if (session()->has('from_date') == false) {
            session()->put('from_date', now()->firstOfMonth()->format('Y-m-d'));
            session()->put('to_date', now()->lastOfMonth()->format('Y-m-d'));
        }
        $from = session('from_date');
        $to = session('to_date');
        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider_id = $request->query('provider_id', 'all');
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
        $filter = $request->query('filter', 'all_time');
        $type = $request->query('type', 'all');

        $expense = Expense::with('trip')->where('amount', '>' ,0)
            ->whereHas('trip', function ($query) use ($zone, $provider, $customer) {
                $query->when(request('module_id'), function ($query) {
                    return $query->module(request('module_id'));
                });
                $query->when($zone, function ($query) use ($zone) {
                    return $query->where('zone_id', $zone->id);
                });
                $query->when($provider, function ($query) use ($provider) {
                    return $query->where('provider_id', $provider->id);
                });
                $query->when($customer, function ($query) use ($customer) {
                    return $query->where('user_id', $customer->id);
                });
            })
            ->when(isset($type) &&  $type != 'all', function ($query) use ($type) {
                return $query->where('type',$type);
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('type', 'like', "%{$value}%")->orWhere('trip_id', 'like', "%{$value}%");
                }
            })
            ->limit(25)->get();

        return response()->json([
            'count' => count($expense),
            'view' => view('rental::admin.report.partials._expense_table', compact('expense'))->render()
        ]);
    }

    public function trip_report(Request $request)
    {
        $key = explode(' ', $request['search']);

        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }
        $from = session('from_date');
        $to = session('to_date');
        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider_id = $request->query('provider_id', 'all');
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
        $filter = $request->query('filter', 'all_time');

        $trips = Order::with(['customer', 'provider', 'details', 'transaction'])
            ->when(request('module_id'), function ($query) {
                return $query->module(request('module_id'));
            })
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->where('zone_id', $zone->id);
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(isset($customer), function ($query) use ($customer) {
                return $query->where('user_id', $customer->id);
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%");
                    }
                });
            })
            ->StoreOrder()
            ->withSum('transaction', 'admin_commission')
            ->withSum('transaction', 'admin_expense')
            ->withSum('transaction', 'delivery_fee_comission')
            ->orderBy('schedule_at', 'desc')->paginate(config('default_pagination'))->withQueryString();

        // trip card values calculation
        $trips_list = Order::when(request('module_id'), function ($query) {
            return $query->module(request('module_id'));
        })
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->where('zone_id', $zone->id);
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(isset($customer), function ($query) use ($customer) {
                return $query->where('user_id', $customer->id);
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%");
                    }
                });
            })
            ->StoreOrder()
            ->orderBy('schedule_at', 'desc')->get();

        $total_trip_amount = $trips_list->sum('trip_amount');
        $total_coupon_discount = $trips_list->sum('coupon_discount_amount');
        $total_product_discount = $trips_list->sum('provider_discount_amount');

        $total_canceled_count = $trips_list->where('trip_status', 'canceled')->count();
        $total_delivered_count = $trips_list->where('trip_status', 'delivered')->count();
        $total_progress_count = $trips_list->whereIn('trip_status', ['accepted', 'confirmed', 'processing', 'handover'])->count();
        $total_failed_count = $trips_list->where('trip_status', 'failed')->count();
        $total_refunded_count = $trips_list->where('trip_status', 'refunded')->count();
        $total_on_the_way_count = $trips_list->whereIn('trip_status', ['picked_up'])->count();
        return view('rental::admin.report.trip-report', compact('trips', 'trips_list', 'zone', 'provider', 'filter', 'customer', 'total_on_the_way_count', 'total_refunded_count', 'total_failed_count', 'total_progress_count', 'total_canceled_count', 'total_delivered_count'));
    }

    public function search_trip_report(Request $request)
    {
        $key = explode(' ', $request['search']);

        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }
        $from = session('from_date');
        $to = session('to_date');
        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider_id = $request->query('provider_id', 'all');
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
        $filter = $request->query('filter', 'all_time');

        $trips = Order::with(['customer', 'provider'])
            ->when(request('module_id'), function ($query) {
                return $query->module(request('module_id'));
            })
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->where('zone_id', $zone->id);
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(isset($customer), function ($query) use ($customer) {
                return $query->where('user_id', $customer->id);
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%");
                }
            })
            ->StoreOrder()
            ->withSum('transaction', 'admin_commission')
            ->withSum('transaction', 'admin_expense')
            ->withSum('transaction', 'delivery_fee_comission')
            ->orderBy('schedule_at', 'desc')->paginate(config('default_pagination'));

        return response()->json([
            'count' => count($trips),
            'view' => view('rental::admin.report.partials._trip_table', compact('trips'))->render()
        ]);
    }

    public function trip_report_export(Request $request)
    {
        $key = isset($request['search']) ? explode(' ', $request['search']) : [];

        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }
        $from = session('from_date');
        $to = session('to_date');
        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider_id = $request->query('provider_id', 'all');
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $customer_id = $request->query('customer_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
        $filter = $request->query('filter', 'all_time');

        $trips = Order::with(['customer', 'provider'])
            ->when(request('module_id'), function ($query) {
                return $query->module(request('module_id'));
            })
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->where('zone_id', $zone->id);
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(isset($customer), function ($query) use ($customer) {
                return $query->where('user_id', $customer->id);
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('schedule_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('schedule_at', now()->format('m'))->whereYear('schedule_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('schedule_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('schedule_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->when(isset($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%");
                    }
                });
            })
            ->StoreOrder()
            ->withSum('transaction', 'admin_commission')
            ->withSum('transaction', 'admin_expense')
            ->withSum('transaction', 'delivery_fee_comission')
            ->orderBy('schedule_at', 'desc')->get();

        $data = [
            'trips'=>$trips,
            'search'=>$request->search??null,
            'from'=>(($filter == 'custom') && $from)?$from:null,
            'to'=>(($filter == 'custom') && $to)?$to:null,
            'zone'=>is_numeric($zone_id)?Helpers::get_zones_name($zone_id):null,
            'provider'=>is_numeric($provider_id)?Helpers::get_providers_name($provider_id):null,
            'customer'=>is_numeric($customer_id)?Helpers::get_customer_name($customer_id):null,
            'module'=>request('module_id')?Helpers::get_module_name(request('module_id')):null,
            'filter'=>$filter,
        ];

        if ($request->type == 'excel') {
            return Excel::download(new OrderReportExport($data), 'OrderReport.xlsx');
        } else if ($request->type == 'csv') {
            return Excel::download(new OrderReportExport($data), 'OrderReport.csv');
        }
    }

    public function expense_report(Request $request)
    {
        $key = explode(' ', $request['search']);
        if (session()->has('from_date') == false) {
            session()->put('from_date', now()->firstOfMonth()->format('Y-m-d'));
            session()->put('to_date', now()->lastOfMonth()->format('Y-m-d'));
        }
        $from = session('from_date');
        $to = session('to_date');
        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider_id = $request->query('provider_id', 'all');
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $customer_id = $request->query('customer_id', 'all');
        $module_id = $request->query('module_id', 'all');
        $customer = is_numeric($customer_id) ? User::findOrFail($customer_id) : null;
        $filter = $request->query('filter', 'all_time');
        $type = $request->query('type', 'all');

        $expense = Expense::with('user','trip', 'trip.customer:id,f_name,l_name')->where('amount', '>' ,0)
            ->when(isset($zone) || isset($provider) || isset($customer), function ($query) use ($zone, $provider, $customer) {
                return $query->whereHas('trip', function ($query) use ($zone, $provider, $customer) {
                    $query->when($zone, function ($query) use ($zone) {
                        return $query->where('zone_id', $zone->id);
                    });
                    $query->when($provider, function ($query) use ($provider) {
                        return $query->where('provider_id', $provider->id);
                    });
                    $query->when($customer, function ($query) use ($customer) {
                        return $query->where('user_id', $customer->id);
                    });
                });
            })
            ->when(isset($type) &&  $type != 'all', function ($query) use ($type) {
                return $query->where('type',$type);
            })
            ->when(isset($module_id) &&  is_numeric($module_id), function ($query) use ($module_id) {
                return $query->whereHas('trip', function ($query) use ($module_id) {
                    $query->when(is_numeric($module_id), function ($query) use ($module_id) {
                        return $query->where('module_id',$module_id);
                    });
                });
            })
            ->when(isset($from) && isset($to) && $from != null && $to != null && $filter == 'custom', function ($query) use ($from, $to) {
                return $query->whereBetween('created_at', [$from . " 00:00:00", $to . " 23:59:59"]);
            })
            ->when(isset($filter) && $filter == 'this_year', function ($query) {
                return $query->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'this_month', function ($query) {
                return $query->whereMonth('created_at', now()->format('m'))->whereYear('created_at', now()->format('Y'));
            })
            ->when(isset($filter) && $filter == 'previous_year', function ($query) {
                return $query->whereYear('created_at', date('Y') - 1);
            })
            ->when(isset($filter) && $filter == 'this_week', function ($query) {
                return $query->whereBetween('created_at', [now()->startOfWeek()->format('Y-m-d H:i:s'), now()->endOfWeek()->format('Y-m-d H:i:s')]);
            })
            ->when(isset($key), function ($query) use ($key){
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('type', 'like', "%{$value}%")->orWhere('trip_id', 'like', "%{$value}%");
                    }
                });
            })
            ->where('created_by', 'admin')
            ->orderBy('created_at', 'desc')
            ->paginate(config('default_pagination'))->withQueryString();


        return view('rental::admin.report.expense-report', compact('expense', 'zone', 'provider', 'filter', 'customer','type'));
    }

    public function generate_statement($id)
    {
        $company_phone = BusinessSetting::where('key', 'phone')->first()->value;
        $company_email = BusinessSetting::where('key', 'email_address')->first()->value;
        $company_name = BusinessSetting::where('key', 'business_name')->first()->value;
        $company_web_logo = BusinessSetting::where('key', 'logo')->first()->value;
        $footer_text = \App\Models\BusinessSetting::where(['key' => 'footer_text'])->first()->value;

        $trip_transaction = TripTransaction::with('trip', 'trip.details', 'trip.customer', 'trip.provider')->where('id', $id)->first();
        $data["email"] = $trip_transaction->trip->customer != null ? $trip_transaction->trip->customer["email"] : translate('email_not_found');
        $data["client_name"] = $trip_transaction->trip->customer != null ? $trip_transaction->trip->customer["f_name"] . ' ' . $trip_transaction->trip->customer["l_name"] : translate('customer_not_found');
        $data["trip_transaction"] = $trip_transaction;
        $mpdf_view = View::make(
            'rental::admin.report.trip-transaction-statement',
            compact('trip_transaction', 'company_phone', 'company_name', 'company_email', 'company_web_logo', 'footer_text')
        );
        Helpers::gen_mpdf($mpdf_view, 'trip_trans_statement', $trip_transaction->id);
    }

    public function low_stock_report(Request $request)
    {
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $provider_id = $request->query('provider_id', 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $stock_modules = array_keys(array_filter(config('module'), function ($var) {
            if (isset($var['stock']) && $var['stock']) return $var;
        }));
        $key = isset($request['search']) ? explode(' ', $request['search']) : [];

        $items = Item::withoutGlobalScope(StoreScope::class)->with(['provider', 'provider.zone'])->whereHas('provider.module', function ($query) {
            $query->where('module_type', '!=', 'food');
        })
            ->when($request->query('module_id', null), function ($query) use ($request) {
                return $query->module($request->query('module_id'));
            })
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->whereIn('provider_id', $zone->providers->pluck('id'));
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(count($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->whereHas('provider.StoreConfig', function ($query) {
                $query->whereColumn('items.stock', '<=', 'provider_configs.minimum_stock_for_warning')->orwhere('items.stock', 0);
            })
            ->orderBy('stock')
            ->paginate(config('default_pagination'))->withQueryString();

        return view('rental::admin.report.low-stock-report', compact('zone', 'provider', 'items'));
    }

    public function low_stock_wise_export(Request $request)
    {
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $provider_id = $request->query('provider_id', 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $stock_modules = array_keys(array_filter(config('module'), function ($var) {
            if (isset($var['stock']) && $var['stock']) return $var;
        }));
        $key = isset($request['search']) ? explode(' ', $request['search']) : [];

        $items = Item::withoutGlobalScope(StoreScope::class)->with(['provider', 'provider.zone'])->whereHas('provider.module', function ($query) {
            $query->where('module_type', '!=', 'food');
        })
            ->when($request->query('module_id', null), function ($query) use ($request) {
                return $query->module($request->query('module_id'));
            })
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->whereIn('provider_id', $zone->providers->pluck('id'));
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(count($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->whereHas('provider.StoreConfig', function ($query) {
                $query->whereColumn('items.stock', '<=', 'provider_configs.minimum_stock_for_warning')->orwhere('items.stock', 0);
            })
            ->orderBy('stock')
            ->get();

        $data = [
            'items'=>$items,
            'search'=>$request->search??null,
            'zone'=>is_numeric($zone_id)?Helpers::get_zones_name($zone_id):null,
            'provider'=>is_numeric($provider_id)?Helpers::get_providers_name($provider_id):null,
        ];

        if ($request->type == 'excel') {
            return Excel::download(new LimitedStockReportExport($data), 'StockReport.xlsx');
        } else if ($request->type == 'csv') {
            return Excel::download(new LimitedStockReportExport($data), 'StockReport.csv');
        }
    }

    public function low_stock_search(Request $request)
    {
        $key = explode(' ', $request['search']);

        $zone_id = $request->query('zone_id', isset(auth('admin')->user()->zone_id) ? auth('admin')->user()->zone_id : 'all');
        $provider_id = $request->query('provider_id', 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $stock_modules = array_keys(array_filter(config('module'), function ($var) {
            if (isset($var['stock']) && $var['stock']) return $var;
        }));
        $key = isset($request['search']) ? explode(' ', $request['search']) : [];

        $items = Item::withoutGlobalScope(StoreScope::class)->with(['provider', 'provider.zone'])->whereHas('provider.module', function ($query) {
            $query->where('module_type', '!=', 'food');
        })
            ->when($request->query('module_id', null), function ($query) use ($request) {
                return $query->module($request->query('module_id'));
            })
            ->when(isset($zone), function ($query) use ($zone) {
                return $query->whereIn('provider_id', $zone->providers->pluck('id'));
            })
            ->when(isset($provider), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when(count($key), function ($query) use ($key) {
                return $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })
            ->orderBy('stock')
            ->limit(25)->get();

        return response()->json([
            'count' => count($items),
            'view' => view('rental::admin.report.partials._stock_table', compact('items'))->render()
        ]);
    }

    public function disbursement_report(Request $request,$tab = 'provider')
    {
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider_id = $request->query('provider_id', 'all');
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $delivery_man_id = $request->query('delivery_man_id', 'all');
        $delivery_man = is_numeric($delivery_man_id) ? DeliveryMan::findOrFail($delivery_man_id) : null;
        $withdrawal_methods = WithdrawalMethod::ofStatus(1)->get();
        $status = $request->query('status', 'all');
        $payment_method_id = $request->query('payment_method_id', 'all');
        $module_id = $request->query('module_id', 'all');

        $dis = DisbursementDetails::
        when((isset($tab) && ($tab == 'provider')), function ($query) {
            return $query->whereNotNull('provider_id');
        })
            ->when((isset($tab) && ($tab == 'delivery_man')), function ($query) {
                return $query->whereNotNull('delivery_man_id');
            })
            ->when((isset($zone) && ($tab == 'provider')), function ($query) use ($zone) {
                return $query->whereHas('provider',function($q)use ($zone){
                    $q->where('zone_id', $zone->id);
                });
            })
            ->when((isset($provider) && ($tab == 'provider')), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when((isset($module_id) &&  is_numeric($module_id)&& ($tab == 'provider')), function ($query) use ($module_id) {
                return $query->whereHas('provider', function ($query) use ($module_id) {
                    $query->where('module_id',$module_id);
                });
            })
            ->when((isset($zone) && ($tab == 'delivery_man')), function ($query) use ($zone) {
                return $query->whereHas('provider',function($q)use ($zone){
                    $q->where('zone_id', $zone->id);
                });
            })
            ->when((isset($delivery_man) && ($tab == 'delivery_man')), function ($query) use ($delivery_man) {
                return $query->where('delivery_man_id', $delivery_man->id);
            })
            ->when((isset($payment_method_id) && ($payment_method_id != 'all')), function ($query) use ($payment_method_id) {
                return $query->whereHas('withdraw_method',function($q)use ($payment_method_id){
                    $q->where('withdrawal_method_id', $payment_method_id);
                });
            })
            ->when((isset($status) && ($status != 'all')), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when(isset($filter) , function ($query) use ($filter,$from, $to) {
                return $query->applyDateFilter($filter, $from, $to);
            })
            ->when(isset($key), function ($q) use ($key) {
                $q->where(function ($query) use ($key) {
                    foreach ($key as $value) {
                        $query->orWhere('disbursement_id', 'like', "%{$value}%")
                            ->orWhere('status', 'like', "%{$value}%")
                            ->orWhereHas('withdraw_method', function ($subQuery) use ($value) {
                                $subQuery->where('method_name','like', "%{$value}%");
                            });
                    }
                });
            })
            ->latest();

        $total_disbursements= $dis->get();

        $disbursements= $dis->paginate(config('default_pagination'))->withQueryString();

        $pending =(float) $total_disbursements->where('status','pending')->sum('disbursement_amount');
        $completed =(float) $total_disbursements->where('status','completed')->sum('disbursement_amount');
        $canceled =(float) $total_disbursements->where('status','canceled')->sum('disbursement_amount');

        return view('rental::admin.report.disbursement-report', compact('disbursements','pending', 'completed','canceled','zone', 'provider','filter','from','to','withdrawal_methods','status','payment_method_id','tab'));

    }
    public function disbursement_report_export(Request $request,$type,$tab = 'provider')
    {
        $from =  null;
        $to = null;
        $filter = $request->query('filter', 'all_time');
        if($filter == 'custom'){
            $from = $request->from ?? null;
            $to = $request->to ?? null;
        }
        $key = explode(' ', $request['search']);
        $zone_id = $request->query('zone_id', isset(auth('admin')?->user()?->zone_id) ? auth('admin')?->user()?->zone_id : 'all');
        $zone = is_numeric($zone_id) ? Zone::findOrFail($zone_id) : null;
        $provider_id = $request->query('provider_id', 'all');
        $provider = is_numeric($provider_id) ? Store::findOrFail($provider_id) : null;
        $delivery_man_id = $request->query('delivery_man_id', 'all');
        $delivery_man = is_numeric($delivery_man_id) ? DeliveryMan::findOrFail($delivery_man_id) : null;
        $withdrawal_methods = WithdrawalMethod::ofStatus(1)->get();
        $status = $request->query('status', 'all');
        $payment_method_id = $request->query('payment_method_id', 'all');
        $module_id = $request->query('module_id', 'all');

        $disbursements = DisbursementDetails::
        when((isset($tab) && ($tab == 'provider')), function ($query) {
            return $query->whereNotNull('provider_id');
        })
            ->when((isset($tab) && ($tab == 'delivery_man')), function ($query) {
                return $query->whereNotNull('delivery_man_id');
            })
            ->when((isset($zone) && ($tab == 'provider')), function ($query) use ($zone) {
                return $query->whereHas('provider',function($q)use ($zone){
                    $q->where('zone_id', $zone->id);
                });
            })
            ->when((isset($provider) && ($tab == 'provider')), function ($query) use ($provider) {
                return $query->where('provider_id', $provider->id);
            })
            ->when((isset($zone) && ($tab == 'delivery_man')), function ($query) use ($zone) {
                return $query->whereHas('provider',function($q)use ($zone){
                    $q->where('zone_id', $zone->id);
                });
            })
            ->when((isset($module_id) &&  is_numeric($module_id)&& ($tab == 'provider')), function ($query) use ($module_id) {
                return $query->whereHas('provider', function ($query) use ($module_id) {
                    $query->where('module_id',$module_id);
                });
            })
            ->when((isset($delivery_man) && ($tab == 'delivery_man')), function ($query) use ($delivery_man) {
                return $query->where('delivery_man_id', $delivery_man->id);
            })
            ->when((isset($payment_method_id) && ($payment_method_id != 'all')), function ($query) use ($payment_method_id) {
                return $query->whereHas('withdraw_method',function($q)use ($payment_method_id){
                    $q->where('withdrawal_method_id', $payment_method_id);
                });
            })
            ->when((isset($status) && ($status != 'all')), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->when(isset($filter) , function ($query) use ($filter,$from, $to) {
                return $query->applyDateFilter($filter, $from, $to);
            })
            ->when(isset($key), function ($q) use ($key) {
                $q->where(function ($query) use ($key) {
                    foreach ($key as $value) {
                        $query->orWhere('disbursement_id', 'like', "%{$value}%")
                            ->orWhere('status', 'like', "%{$value}%")
                            ->orWhereHas('withdraw_method', function ($subQuery) use ($value) {
                                $subQuery->where('method_name','like', "%{$value}%");
                            });
                    }
                });
            })
            ->latest()->get();

        $data=[
            'type'=>$tab,
            'disbursements' =>$disbursements,
            'provider'=>isset($provider)?$provider->name:null,
            'delivery_man'=>isset($delivery_man)?$delivery_man->f_name.''.$delivery_man->f_name:null,
            'search'=>$request->search??null,
            'status'=>$status,
            'zone'=>isset($zone)?$zone->name:null,
            'filter'=>$filter,
            'from'=>(($filter == 'custom') && $from)?$from:null,
            'to'=>(($filter == 'custom') && $to)?$to:null,
            'pending' =>(float) $disbursements->where('status','pending')->sum('disbursement_amount'),
            'completed' =>(float) $disbursements->where('status','completed')->sum('disbursement_amount'),
            'canceled' =>(float) $disbursements->where('status','canceled')->sum('disbursement_amount'),
        ];
        if($type == 'csv'){
            return Excel::download(new DisbursementReportExport($data), 'DisbursementReport.csv');
        }
        return Excel::download(new DisbursementReportExport($data), 'DisbursementReport.xlsx');

    }
}


<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class VendoTaxReportController extends Controller
{

    public function vendorTax(Request $request)
    {

        $dateRange = $request->dates ?? now()->startOfYear()->format('m/d/Y') . ' - ' . now()->endOfYear()->format('m/d/Y');
        $key = explode(' ', $request['search']);

        list($startDate, $endDate) = explode(' - ', $dateRange);
        $startDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($startDate));
        $endDate = \Carbon\Carbon::createFromFormat('m/d/Y', trim($endDate));
        $startDate = $startDate->startOfDay();
        $endDate = $endDate->endOfDay();

        $start = microtime(true);

        $query = DB::table('orders')
            ->selectRaw('COUNT(*) as total_orders,
                        SUM(order_amount) as total_order_amount,
                        SUM(total_tax_amount) as total_tax')
            ->whereIn('order_status', ['delivered', 'refund_requested', 'refund_request_canceled']);

        if (isset($store)) {
            $query->where('store_id', $store->id);
        }

        if (isset($startDate) && isset($endDate)) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if (isset($key)) {
            $query->whereExists(function ($subQuery) use ($key) {
                $subQuery->select(DB::raw(1))
                    ->from('stores')
                    ->whereRaw('stores.id = orders.store_id')
                    ->where(function ($q) use ($key) {
                        foreach ($key as $value) {
                            $q->orWhere('stores.name', 'like', "%{$value}%");
                        }
                    });
            });
        }

        $result = $query->first();

        $totalOrders = $result->total_orders;
        $totalOrderAmount = $result->total_order_amount;
        $totalTax = $result->total_tax;

        $storeQuery = DB::table('stores as stores')
            ->selectRaw(' stores.id as store_id,
                            stores.name as store_name,
                            stores.phone as store_phone,
                            COUNT(DISTINCT orders.id) as total_orders,
                            SUM(orders.order_amount) as total_order_amount ')
            ->join('orders as orders', function ($join) use ($startDate, $endDate) {
                $join->on('orders.store_id', '=', 'stores.id')
                    ->whereIn('orders.order_status', ['delivered', 'refund_requested', 'refund_request_canceled']);

                if ($startDate && $endDate) {
                    $join->whereBetween('orders.created_at', [$startDate, $endDate]);
                }
            })
            ->when(isset($store), fn($query) => $query->where('stores.id', $store->id))
            ->when(!empty($key), function ($query) use ($key) {
                $query->where(function ($q) use ($key) {
                    foreach ($key as $searchTerm) {
                        $q->orWhere('stores.name', 'like', "%{$searchTerm}%");
                    }
                });
            })

            ->groupBy('stores.id')
            ->paginate(config('default_pagination'))->withQueryString();

        $storeIds = $storeQuery->pluck('store_id')->toArray();

        $taxQuery = DB::table('order_taxes as order_taxes')
            ->selectRaw('orders.store_id, order_taxes.tax_name, SUM(order_taxes.tax_amount) as total_tax_amount')
            ->join('orders', 'order_taxes.order_id', '=', 'orders.id')
            ->where('order_taxes.order_type', 'App\\Models\\Order')
            ->whereIn('orders.order_status', ['delivered', 'refund_requested', 'refund_request_canceled'])
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('orders.created_at', [$startDate, $endDate]);
            })
            ->whereIn('orders.store_id',  $storeIds)

            ->groupBy('orders.store_id', 'order_taxes.tax_name')
            ->get();

        $taxGrouped = [];
        // dd($taxQuery);
        foreach ($taxQuery as $tax) {
            $taxGrouped[$tax->store_id][] = [
                'tax_name' => $tax->tax_name,
                'total_tax_amount' => (float)$tax->total_tax_amount,
            ];
        }

        $stores = $storeQuery->getCollection()->map(function ($store) use ($taxGrouped) {
            return (object)[
                'store_id' => $store->store_id,
                'store_name' => $store->store_name,
                'store_phone' => $store->store_phone,
                'total_orders' => (int)$store->total_orders,
                'total_order_amount' => (float)$store->total_order_amount,
                'tax_data' => $taxGrouped[$store->store_id] ?? [],
            ];
        });

        $stores = $storeQuery->setCollection($stores);
        $time = microtime(true) - $start;
        // dd("Query took {$time} seconds", $stores);
        return view('admin-views.report.tax-report.vendor-tax-report', compact('totalOrders', 'totalOrderAmount', 'totalTax', 'stores'));
    }
}

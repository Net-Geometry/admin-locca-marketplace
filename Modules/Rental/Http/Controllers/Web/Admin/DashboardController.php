<?php

namespace Modules\Rental\Http\Controllers\Web\Admin;

use App\Models\DeliveryMan;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderTransaction;
use App\Models\Store;
use App\Models\User;
use App\Models\Wishlist;
use App\Scopes\ZoneScope;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function __construct()
    {
        DB::statement("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));");
    }

    public function dashboard(Request $request)
    {
        $params = [
            'zone_id' => $request['zone_id'] ?? 'all',
            'module_id' => Config::get('module.current_module_id'),
            'statistics_type' => $request['statistics_type'] ?? 'overall',
            'user_overview' => $request['user_overview'] ?? 'overall',
            'commission_overview' => $request['commission_overview'] ?? 'this_year',
            'business_overview' => $request['business_overview'] ?? 'overall',
        ];

        $module_type = Config::get('module.current_module_type');
        if($module_type == 'settings'){
            return redirect()->route('admin.business-settings.business-setup');
        }
        return view("rental::admin.dashboard-{$module_type}", compact('params','module_type'));

    }

}

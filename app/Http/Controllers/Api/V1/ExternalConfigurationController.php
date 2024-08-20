<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ExternalConfigurationController extends Controller
{
    public function getConfiguration()
    {
        $name = \App\Models\BusinessSetting::where('key', 'business_name')->first();
        $logo = \App\Models\BusinessSetting::where('key', 'logo')->first();
        $configs = [
            'business_name' => $name?->value ?? "6amMart",
            'logo' => \App\CentralLogics\Helpers::get_full_url('business', $logo?->value ?? '', $logo?->storage[0]?->value ?? 'public', 'favicon') ?? asset('public/assets/admin/img/160x160/img2.jpg'),
        ];
        return response()->json($configs);
    }

    public function updateConfiguration(Request $request)
    {
        DB::table('external_configurations')->updateOrInsert(['key' => 'drivemond_business_name'], [
            'value' => $request['drivemond_business_name']
        ]);

        DB::table('external_configurations')->updateOrInsert(['key' => 'drivemond_business_logo'], [
            'value' => $request['drivemond_business_logo']
        ]);
        return response()->json(['message' => 'Configuration updated successfully.']);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ExternalConfigurationController extends Controller
{
    public function getConfiguration()
    {
        $name =BusinessSetting::where('key', 'business_name')->first();
        $logo = BusinessSetting::where('key', 'logo')->first();


        $app_minimum_version_android=BusinessSetting::where(['key'=>'app_minimum_version_android'])->first()?->value;
        $app_url_android=BusinessSetting::where(['key'=>'app_url_android'])->first()?->value;
        $app_minimum_version_ios=BusinessSetting::where(['key'=>'app_minimum_version_ios'])->first()?->value;
        $app_url_ios=BusinessSetting::where(['key'=>'app_url_ios'])->first()?->value;

        $configs = [
            'business_name' => $name?->value ?? "6amMart",
            'logo' => \App\CentralLogics\Helpers::get_full_url('business', $logo?->value ?? '', $logo?->storage[0]?->value ?? 'public', 'favicon') ?? asset('public/assets/admin/img/160x160/img2.jpg'),
            'app_minimum_version_android' => $app_minimum_version_android,
            'app_url_android' => $app_url_android,
            'app_minimum_version_ios' => $app_minimum_version_ios,
            'app_url_ios' => $app_url_ios,
        ];
        return response()->json($configs);
    }

    public function updateConfiguration(Request $request)
    {
        if($request?->drivemond_business_name){
            DB::table('external_configurations')->updateOrInsert(['key' => 'drivemond_business_name'], [
                'value' => $request?->drivemond_business_name
            ]);
        }

        if( $request?->drivemond_business_logo){
            DB::table('external_configurations')->updateOrInsert(['key' => 'drivemond_business_logo'], [
                'value' => $request?->drivemond_business_logo
            ]);
        }

        if( $request?->drivemond_app_url_android){
            DB::table('external_configurations')->updateOrInsert(['key' => 'drivemond_app_url_android'], [
                'value' => $request?->drivemond_app_url_android
            ]);
        }
        if($request?->drivemond_app_url_ios){
            DB::table('external_configurations')->updateOrInsert(['key' => 'drivemond_app_url_ios'], [
                'value' => $request?->drivemond_app_url_ios
            ]);
        }
        return response()->json(['message' => 'Configuration updated successfully.']);
    }
}

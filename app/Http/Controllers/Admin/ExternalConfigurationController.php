<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExternalConfigurationController extends Controller
{
    public function index()
    {
        return view('admin-views.external-configuration.external-index');
    }
    public function updateDrivemondConfiguration(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.update_option_is_disable_for_demo'));
            return back();
        }
        if (array_key_exists('activation_mode',$request->all())){
            DB::table('external_configurations')->updateOrInsert(['key' => 'activation_mode'], [
                'value' => 1
            ]);
        }else{
            DB::table('external_configurations')->updateOrInsert(['key' => 'activation_mode'], [
                'value' => 0
            ]);
        }

        DB::table('external_configurations')->updateOrInsert(['key' => 'drivemond_base_url'], [
            'value' => $request['drivemond_base_url']
        ]);

        DB::table('external_configurations')->updateOrInsert(['key' => 'drivemond_token'], [
            'value' => $request['drivemond_token']
        ]);
        DB::table('external_configurations')->updateOrInsert(['key' => 'system_self_token'], [
            'value' => $request['system_self_token']
        ]);

        Toastr::success(translate('messages.successfully_updated_to_changes_restart_app'));
        return back();
    }

}

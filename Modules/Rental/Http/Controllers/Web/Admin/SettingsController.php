<?php

namespace Modules\Rental\Http\Controllers\Web\Admin;

use App\Models\DataSetting;
use Illuminate\Http\Request;
use App\Traits\FileManagerTrait;
use Illuminate\Routing\Controller;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;

class SettingsController extends Controller
{

    use FileManagerTrait;

    public function __construct(private DataSetting $settings)
    {
        $this->settings = $settings;
    }
    public function homePageDownApp()
    {
        return view('rental::admin.home-page-setup.download-app', [
            'title_data' => $this->settings->where('key', 'module_home_page_data_title')->withoutGlobalScope('translate')->with('translations')->first(),
            'sub_title_data' => $this->settings->where('key', 'module_home_page_data_sub_title')->withoutGlobalScope('translate')->with('translations')->first(),
            'image' =>  $this->settings->where('key', 'module_home_page_data_image')->first(),
            'language'=> getWebConfig('language'),
        ]);
    }

    public function homePageDownAppUpdate(Request $request)
    {
        $request->validate([
            'title.*' => 'max:30',
            'title.0' => 'required',
            'sub_title.*' => 'max:110',
            'sub_title.0' => 'required',
            'image' => 'nullable|max:2048',
        ],
        [
            'title.0.required'=>translate('default_title_is_required'),
            'sub_title.0.required'=>translate('default_sub_title_is_required'),
            'title.*.max'=>translate('max_title_length_is_30_char'),
            'sub_title.*.required'=>translate('max_sub_title_length_is_120_char'),
        ]);

        $title = $this->settings->where('key', 'module_home_page_data_title')->firstOrNew();
        $title->type = 'module_home_page_data';
        $title->key = 'module_home_page_data_title';
        $title->value = $request->title[array_search('default', $request->lang)];
        $title->save();

        $sub_title = $this->settings->where('key', 'module_home_page_data_sub_title')->firstOrNew();
        $sub_title->type = 'module_home_page_data';
        $sub_title->key = 'module_home_page_data_sub_title';
        $sub_title->value = $request->sub_title[array_search('default', $request->lang)];
        $sub_title->save();
            Helpers::add_or_update_translations(request: $request, key_data: 'module_home_page_data_title', name_field: 'title', model_name: 'DataSetting', data_id: $title->id, data_value: $title->value);

            Helpers::add_or_update_translations(request: $request, key_data: 'module_home_page_data_sub_title', name_field: 'sub_title', model_name: 'DataSetting', data_id: $sub_title->id, data_value: $sub_title->value);

        if ($request->hasFile('image')) {
            $image = $this->settings->where('key', 'module_home_page_data_image')->firstOrNew();
            $image->type = 'module_home_page_data';
            $image->key = 'module_home_page_data_image';

            if (empty($image->value)) {
                $image->value  = $this->upload('react_landing/', 'png', $request->file('image'));
            } else {
                $image->value  = $this->updateAndUpload('react_landing/', $image->value, 'png', $request->file('image'));
            }
            $image->save();
        }
        Toastr::success(translate('messages.Download_app_section_data_updated_successfully'));
        return back();

    }
    public function vendorsRegistration()
    {
        return view('rental::admin.home-page-setup.vendor-registration', [
            'title_data' => $this->settings->where('key', 'module_vendor_registration_data_title')->withoutGlobalScope('translate')->with('translations')->first(),
            'sub_title_data' => $this->settings->where('key', 'module_vendor_registration_data_sub_title')->withoutGlobalScope('translate')->with('translations')->first(),
            'button_title_data' => $this->settings->where('key', 'module_vendor_registration_data_button_title')->withoutGlobalScope('translate')->with('translations')->first(),
            'image' =>  $this->settings->where('key', 'module_vendor_registration_data_image')->first(),
            'language'=> getWebConfig('language'),
        ]);
    }
    public function vendorsRegistrationUpdate(Request $request)
    {
        $request->validate([
            'title.*' => 'max:30',
            'title.0' => 'required',
            'sub_title.*' => 'max:110',
            'sub_title.0' => 'required',
            'button_title.*' => 'max:110',
            'button_title.0' => 'required',
            'image' => 'nullable|max:2048',
        ],
        [
            'title.0.required'=>translate('default_title_is_required'),
            'sub_title.0.required'=>translate('default_sub_title_is_required'),
            'button_title.0.required'=>translate('default_button_title_is_required'),
            'title.*.max'=>translate('max_title_length_is_30_char'),
            'sub_title.*.required'=>translate('max_sub_title_length_is_120_char'),
            'button_title.*.required'=>translate('max_button_title_length_is_20_char'),
        ]);

        $title = $this->settings->where('key', 'module_vendor_registration_data_title')->firstOrNew();
        $title->type = 'module_vendor_registration_data';
        $title->key = 'module_vendor_registration_data_title';
        $title->value = $request->title[array_search('default', $request->lang)];
        $title->save();

        $sub_title = $this->settings->where('key', 'module_vendor_registration_data_sub_title')->firstOrNew();
        $sub_title->type = 'module_vendor_registration_data';
        $sub_title->key = 'module_vendor_registration_data_sub_title';
        $sub_title->value = $request->sub_title[array_search('default', $request->lang)];
        $sub_title->save();

        $button_title = $this->settings->where('key', 'module_vendor_registration_data_button_title')->firstOrNew();
        $button_title->type = 'module_vendor_registration_data';
        $button_title->key = 'module_vendor_registration_data_button_title';
        $button_title->value = $request->button_title[array_search('default', $request->lang)];
        $button_title->save();

            Helpers::add_or_update_translations(request: $request, key_data: 'module_vendor_registration_data_title', name_field: 'title', model_name: 'DataSetting', data_id: $title->id, data_value: $title->value);

            Helpers::add_or_update_translations(request: $request, key_data: 'module_vendor_registration_data_sub_title', name_field: 'sub_title', model_name: 'DataSetting', data_id: $sub_title->id, data_value: $sub_title->value);

            Helpers::add_or_update_translations(request: $request, key_data: 'module_vendor_registration_data_button_title', name_field: 'button_title', model_name: 'DataSetting', data_id: $button_title->id, data_value: $button_title->value);

        if ($request->hasFile('image')) {
            $image = $this->settings->where('key', 'module_vendor_registration_data_image')->firstOrNew();
            $image->type = 'module_vendor_registration_data';
            $image->key = 'module_vendor_registration_data_image';

            if (empty($image->value)) {
                $image->value  = $this->upload('react_landing/', 'png', $request->file('image'));
            } else {
                $image->value  = $this->updateAndUpload('react_landing/', $image->value, 'png', $request->file('image'));
            }
            $image->save();
        }
        Toastr::success(translate('messages.vendor_registration_section_data_updated_successfully'));
        return back();

    }




}

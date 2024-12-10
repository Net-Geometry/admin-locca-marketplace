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

        $type = 'module_home_page_data';
        $fields = ['title', 'sub_title'];
        foreach ($fields as $field) {
            $this->updateSettingAndTranslations($request, "{$type}_{$field}", $type, $field, $request->lang, 'DataSetting');
        }

        if ($request->hasFile('image')) {
            $this->updateImageSetting($request->file('image'), 'module_home_page_data_image', 'react_landing/', $type);
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

        $type = 'module_vendor_registration_data';

        $fields = ['title', 'sub_title', 'button_title'];
        foreach ($fields as $field) {
            $this->updateSettingAndTranslations($request, "{$type}_{$field}", $type, $field, $request->lang, 'DataSetting');
        }

        if ($request->hasFile('image')) {
            $this->updateImageSetting($request->file('image'), 'module_vendor_registration_data_image', 'react_landing/', $type);
        }

        Toastr::success(translate('messages.vendor_registration_section_data_updated_successfully'));
        return back();

    }

    
    private function updateSettingAndTranslations($request, $key, $type, $field, $lang, $modelName)
    {
        $setting = $this->settings->where('key', $key)->firstOrNew();
        $setting->type = $type;
        $setting->key = $key;
        $setting->value = $request->$field[array_search('default', $lang)];
        $setting->save();

        Helpers::add_or_update_translations(
            request: $request,
            key_data: $key,
            name_field: $field,
            model_name: $modelName,
            data_id: $setting->id,
            data_value: $setting->value
        );
        return true;
    }


    private function updateImageSetting($imageFile, $key, $path, $type)
    {
        $imageSetting = $this->settings->where('key', $key)->firstOrNew();
        $imageSetting->type = $type;
        $imageSetting->key = $key;

        if (empty($imageSetting->value)) {
            $imageSetting->value = $this->upload($path, 'png', $imageFile);
        } else {
            $imageSetting->value = $this->updateAndUpload($path, $imageSetting->value, 'png', $imageFile);
        }
        $imageSetting->save();
        return true;
    }

}

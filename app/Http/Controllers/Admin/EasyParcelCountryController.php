<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\EasyParcelCountry;
use App\Models\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class EasyParcelCountryController extends Controller
{
    public function country(Request $request)
    {
        $language = getWebConfig('language');
        if ($request->has('search')) {
            $easyPercelCountries = EasyParcelCountry::where('name', 'like', '%' . $request->search . '%')
                ->orWhere('country_code', 'like', '%' . $request->search . '%')
                ->paginate(config('default_pagination'));
        } else {
            $easyPercelCountries = EasyParcelCountry::paginate(config('default_pagination'));
        }
        return view('admin-views.easyparcel.country.index', compact('language','easyPercelCountries'));
    }
    public function countryStore(Request $request){
   
        $easyParcelCountry = new EasyParcelCountry();
        $easyParcelCountry->name =$request->name[array_search('default', $request->lang)];
        $easyParcelCountry->country_code = $request->country_code;
        $easyParcelCountry->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach($request->lang as $index=>$key)
        {
            if($default_lang == $key && !($request->name[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\EasyParcelCountry',
                            'translationable_id' => $easyParcelCountry->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $easyParcelCountry->name]
                    );
                }
            }else{

                if ($request->name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\EasyParcelCountry',
                            'translationable_id'    => $easyParcelCountry->id,
                            'locale'                => $key,
                            'key'                   => 'name'],
                        ['value'                 => $request->name[$index]]
                    );
                }
            }
        }

        Toastr::success(translate('messages.country_added_successfully'));
        return redirect()->route('admin.business-settings.easy-parcel.country.index');

        
    }
    public function countryDelete($id){
        $easyParcelCountry = EasyParcelCountry::find($id);
        if ($easyParcelCountry) {
            $easyParcelCountry->delete();
            Toastr::success(translate('messages.country_deleted_successfully'));
        } else {
            Toastr::error(translate('messages.country_not_found'));
        }
        return redirect()->route('admin.business-settings.easy-parcel.country.index');
    }

    public function countryStatusUpdate(Request $request)
    {
        $easyParcelCountry = EasyParcelCountry::find($request->id);
        if ($easyParcelCountry) {
            $easyParcelCountry->status = $request->status;
            $easyParcelCountry->save();
            Toastr::success(translate('messages.country_status_updated'));
        } else {
            Toastr::error(translate('messages.country_not_found'));
        }
        return redirect()->route('admin.business-settings.easy-parcel.country.index');
    }
    public function countryEdit($id)
    {
        $language = getWebConfig('language');
        $country = EasyParcelCountry::withoutGlobalScope('translate')->find($id);
        if (!$country) {
            Toastr::error(translate('messages.country_not_found'));
            return redirect()->route('admin.business-settings.easy-parcel.country.index');
        }
        $language = getWebConfig('language');
        return view('admin-views.easyparcel.country.edit', compact('language','country'));
    }

    public function countryUpdate(Request $request, $id)
    {
        $easyParcelCountry = EasyParcelCountry::find($id);
        if (!$easyParcelCountry) {
            Toastr::error(translate('messages.country_not_found'));
            return redirect()->route('admin.business-settings.easy-parcel.country.index');
        }
        $easyParcelCountry->name = $request->name[array_search('default', $request->lang)];
        $easyParcelCountry->country_code = $request->country_code;
        $easyParcelCountry->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->name[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\EasyParcelCountry',
                            'translationable_id' => $easyParcelCountry->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $easyParcelCountry->name]
                    );
                }
            } else {
                if ($request->name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\EasyParcelCountry',
                            'translationable_id'    => $easyParcelCountry->id,
                            'locale'                => $key,
                            'key'                   => 'name'],
                        ['value'                 => $request->name[$index]]
                    );
                }
            }
        }

        Toastr::success(translate('messages.country_updated_successfully'));
        return redirect()->route('admin.business-settings.easy-parcel.country.index');
    }
}
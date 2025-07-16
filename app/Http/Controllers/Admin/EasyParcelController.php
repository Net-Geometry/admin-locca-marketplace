<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\EasyParcelCity;
use App\Models\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class EasyParcelController extends Controller
{
    public function city(Request $request)
    {
        $language = getWebConfig('language');
        if ($request->has('search')) {
            $easyPercelCites = EasyParcelCity::where('name', 'like', '%' . $request->search . '%')
                ->orWhere('country_code', 'like', '%' . $request->search . '%')
                ->paginate(config('default_pagination'));
        } else {
            $easyPercelCites = EasyParcelCity::paginate(config('default_pagination'));
        }
        return view('admin-views.easyparcel.city.index', compact('language','easyPercelCites'));
    }
    public function cityStore(Request $request){
   
        $easyParcelCity = new EasyParcelCity();
        $easyParcelCity->name =$request->name[array_search('default', $request->lang)];
        $easyParcelCity->country_code = $request->country_code;
        $easyParcelCity->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach($request->lang as $index=>$key)
        {
            if($default_lang == $key && !($request->name[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\EasyParcelCity',
                            'translationable_id' => $easyParcelCity->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $easyParcelCity->name]
                    );
                }
            }else{

                if ($request->name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\EasyParcelCity',
                            'translationable_id'    => $easyParcelCity->id,
                            'locale'                => $key,
                            'key'                   => 'name'],
                        ['value'                 => $request->name[$index]]
                    );
                }
            }
        }

        Toastr::success(translate('messages.city_added_successfully'));
        return redirect()->route('admin.business-settings.easy-parcel.city.index');

        
    }
    public function cityDelete($id){
        $easyParcelCity = EasyParcelCity::find($id);
        if ($easyParcelCity) {
            $easyParcelCity->delete();
            Toastr::success(translate('messages.city_deleted_successfully'));
        } else {
            Toastr::error(translate('messages.city_not_found'));
        }
        return redirect()->route('admin.business-settings.easy-parcel.city.index');
    }

    public function cityStatusUpdate(Request $request)
    {
        $easyParcelCity = EasyParcelCity::find($request->id);
        if ($easyParcelCity) {
            $easyParcelCity->status = $request->status;
            $easyParcelCity->save();
            Toastr::success(translate('messages.city_status_updated'));
        } else {
            Toastr::error(translate('messages.city_not_found'));
        }
        return redirect()->route('admin.business-settings.easy-parcel.city.index');
    }
    public function cityEdit($id)
    {
        $language = getWebConfig('language');
        $city = EasyParcelCity::withoutGlobalScope('translate')->find($id);
        if (!$city) {
            Toastr::error(translate('messages.city_not_found'));
            return redirect()->route('admin.business-settings.easy-parcel.city.index');
        }
        $language = getWebConfig('language');
        return view('admin-views.easyparcel.city.edit', compact('language','city'));
    }

    public function cityUpdate(Request $request, $id)
    {
        $easyParcelCity = EasyParcelCity::find($id);
        if (!$easyParcelCity) {
            Toastr::error(translate('messages.city_not_found'));
            return redirect()->route('admin.business-settings.easy-parcel.city.index');
        }
        $easyParcelCity->name = $request->name[array_search('default', $request->lang)];
        $easyParcelCity->country_code = $request->country_code;
        $easyParcelCity->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->name[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\EasyParcelCity',
                            'translationable_id' => $easyParcelCity->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $easyParcelCity->name]
                    );
                }
            } else {
                if ($request->name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\EasyParcelCity',
                            'translationable_id'    => $easyParcelCity->id,
                            'locale'                => $key,
                            'key'                   => 'name'],
                        ['value'                 => $request->name[$index]]
                    );
                }
            }
        }

        Toastr::success(translate('messages.city_updated_successfully'));
        return redirect()->route('admin.business-settings.easy-parcel.city.index');
    }
}
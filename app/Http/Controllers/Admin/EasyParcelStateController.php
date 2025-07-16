<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\EasyParcelCountry;
use App\Models\EasyParcelState;
use App\Models\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class EasyParcelStateController extends Controller
{
    public function state(Request $request)
    {
        $language = getWebConfig('language');
        if ($request->has('search')) {
            $easyPercelStates = EasyParcelState::where('name', 'like', '%' . $request->search . '%')
                ->orWhere('state_code', 'like', '%' . $request->search . '%')
                ->paginate(config('default_pagination'));
        } else {
            $easyPercelStates = EasyParcelState::paginate(config('default_pagination'));
        }
        $countries=EasyParcelCountry::all();
        return view('admin-views.easyparcel.state.index', compact('language','easyPercelStates','countries'));
    }
    public function stateStore(Request $request){
        $request->validate([
            'name' => 'required|array',
            'name.0' => 'required',
            'country_id' => 'required',
            'state_code' => 'required|string|max:10',
            'lang' => 'required|array',
            'lang.*' => 'required|string|max:10',
        ]);

        $easyParcelState = new EasyParcelState();
        $easyParcelState->name =$request->name[array_search('default', $request->lang)];
        $easyParcelState->state_code = $request->state_code;
        $easyParcelState->easy_parcel_country_id = $request->country_id;
        $easyParcelState->save();
        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach($request->lang as $index=>$key)
        {
            if($default_lang == $key && !($request->name[$index])){
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\EasyParcelState',
                            'translationable_id' => $easyParcelState->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $easyParcelState->name]
                    );
                }
            }else{

                if ($request->name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\EasyParcelState',
                            'translationable_id'    => $easyParcelState->id,
                            'locale'                => $key,
                            'key'                   => 'name'],
                        ['value'                 => $request->name[$index]]
                    );
                }
            }
        }

        Toastr::success(translate('messages.state_added_successfully'));
        return redirect()->route('admin.business-settings.easy-parcel.state.index');

        
    }
    public function stateDelete($id){
        $easyParcelState = EasyParcelState::find($id);
        if ($easyParcelState) {
            $easyParcelState->delete();
            Toastr::success(translate('messages.state_deleted_successfully'));
        } else {
            Toastr::error(translate('messages.state_not_found'));
        }
        return redirect()->route('admin.business-settings.easy-parcel.state.index');
    }

    public function stateStatusUpdate(Request $request)
    {
        $easyParcelState = EasyParcelState::find($request->id);
        if ($easyParcelState) {
            $easyParcelState->status = $request->status;
            $easyParcelState->save();
            Toastr::success(translate('messages.state_status_updated'));
        } else {
            Toastr::error(translate('messages.state_not_found'));
        }
        return redirect()->route('admin.business-settings.easy-parcel.state.index');
    }
    public function stateEdit($id)
    {
        $language = getWebConfig('language');
        $state = EasyParcelState::withoutGlobalScope('translate')->find($id);
        if (!$state) {
            Toastr::error(translate('messages.state_not_found'));
            return redirect()->route('admin.business-settings.easy-parcel.state.index');
        }
        $language = getWebConfig('language');
        return view('admin-views.easyparcel.state.edit', compact('language','state'));
    }

    public function stateUpdate(Request $request, $id)
    {
        $easyParcelState = EasyParcelState::find($id);
        if (!$easyParcelState) {
            Toastr::error(translate('messages.state_not_found'));
            return redirect()->route('admin.business-settings.easy-parcel.state.index');
        }
        $easyParcelState->name = $request->name[array_search('default', $request->lang)];
        $easyParcelState->state_code = $request->state_code;
        $easyParcelState->save();

        $default_lang = str_replace('_', '-', app()->getLocale());
        foreach ($request->lang as $index => $key) {
            if ($default_lang == $key && !($request->name[$index])) {
                if ($key != 'default') {
                    Translation::updateOrInsert(
                        [
                            'translationable_type' => 'App\Models\EasyParcelState',
                            'translationable_id' => $easyParcelState->id,
                            'locale' => $key,
                            'key' => 'name'
                        ],
                        ['value' => $easyParcelState->name]
                    );
                }
            } else {
                if ($request->name[$index] && $key != 'default') {
                    Translation::updateOrInsert(
                        ['translationable_type'  => 'App\Models\EasyParcelState',
                            'translationable_id'    => $easyParcelState->id,
                            'locale'                => $key,
                            'key'                   => 'name'],
                        ['value'                 => $request->name[$index]]
                    );
                }
            }
        }

        Toastr::success(translate('messages.state_updated_successfully'));
        return redirect()->route('admin.business-settings.easy-parcel.state.index');
    }
}
<?php

namespace App\Services;

use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\Config;

class BannerService
{

    public function getAddData(Object $request): array
    {
        return [
            'title' => $request->title[array_search('default', $request->lang)],
            'type' => $request->banner_type,
            'zone_id' => $request->zone_id,
            'image' => Helpers::upload('banner/', 'png', $request->file('image')),
            'data' => ($request->banner_type == 'store_wise')?$request->store_id:(($request->banner_type == 'item_wise')?$request->item_id:''),
            'module_id' => Config::get('module.current_module_id'),
            'default_link' => $request->default_link
        ];
    }

}

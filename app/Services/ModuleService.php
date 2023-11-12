<?php

namespace App\Services;

use App\CentralLogics\Helpers;
use Composer\DependencyResolver\Request;

class ModuleService
{

    public function getAddData(Object $request): array
    {
        return [
            'module_name' => $request->module_name[array_search('default', $request->lang)],
            'icon' => Helpers::upload('module/', 'png', $request->file('icon')),
            'thumbnail' => Helpers::upload('module/', 'png', $request->file('thumbnail')),
            'module_type' => $request->module_type,
            'theme_id' => 1,
            'description' => $request->description[array_search('default', $request->lang)],
        ];
    }
    public function getUpdateData(Object $request): array
    {
        return [
            'module_name' => $request->module_name[array_search('default', $request->lang)],
            'icon' => Helpers::upload('module/', 'png', $request->file('icon')),
            'thumbnail' => Helpers::upload('module/', 'png', $request->file('thumbnail')),
            'theme_id' => 1,
            'description' => $request->description[array_search('default', $request->lang)],
            'all_zone_service' => false,
        ];
    }

    public function getDropdownData(Object $data, object $request): array
    {

        $formattedData = $data->map(function ($condition) {
            return [
                'id' => $condition->id,
                'text' => $condition->name,
            ];
        });


        if(isset($request->all))
        {
            $formattedData[]=(object)['id'=>'all', 'text'=>translate('messages.all')];
        }

        return $formattedData;
    }

}

<?php

namespace App\Services;

use Composer\DependencyResolver\Request;

class CommonConditionService
{

    public function getAddData(Object $request): array
    {
        return [
            'name' => $request->name[array_search('default', $request->lang)],
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

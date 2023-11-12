<?php

namespace App\Services;

use App\CentralLogics\Helpers;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class CustomRoleService
{

    public function getAddData(Object $request): array
    {
        return [
            'name' => $request->name[array_search('default', $request->lang)],
            'modules' => json_encode($request['modules']),
            'status' => 1,
        ];
    }

}

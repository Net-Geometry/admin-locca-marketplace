<?php

namespace App\Services;

use App\CentralLogics\Helpers;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class EmployeeService
{

    public function getAddData(Object $request): array
    {
        if ($request->role_id == 1) {
            return ['flag' => 'unauthorized'];
        }
        return [
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'phone' => $request->phone,
            'zone_id' => $request->zone_id,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => bcrypt($request->password),
            'image' => Helpers::upload('admin/', 'png', $request->file('image')),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

}

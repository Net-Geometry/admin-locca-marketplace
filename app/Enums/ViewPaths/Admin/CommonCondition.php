<?php

namespace App\Enums\ViewPaths\Admin;

enum CommonCondition
{
    const DROPDOWN = [
        URI => '/get',
        VIEW => ''
    ];

    const INDEX = [
        URI => '/',
        VIEW => 'admin-views.common-condition.index'
    ];

    const ADD = [
        URI => 'store',
        VIEW => 'admin-views.common-condition.index'
    ];

    const UPDATE = [
        URI => 'edit/{id}',
        VIEW => 'admin-views.common-condition.edit'
    ];

    const DELETE = [
        URI => 'delete/{id}',
        VIEW => ''
    ];

    const STATUS = [
        URI => 'status/{id}/{status}',
        VIEW => ''
    ];
}

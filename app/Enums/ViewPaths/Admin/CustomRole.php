<?php

namespace App\Enums\ViewPaths\Admin;

enum CustomRole
{
    const INDEX = [
        URI => '/',
        VIEW => 'admin-views.custom-role.create'
    ];

    const ADD = [
        URI => 'store',
        VIEW => 'admin-views.custom-role.index'
    ];

    const UPDATE = [
        URI => 'edit/{id}',
        VIEW => 'admin-views.custom-role.edit'
    ];

    const DELETE = [
        URI => 'delete/{id}',
        VIEW => ''
    ];
    const SEARCH = [
        URI => 'search',
        VIEW => 'admin-views.custom-role.partials._table'
    ];
}

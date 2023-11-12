<?php

namespace App\Enums\ViewPaths\Admin;

enum CustomRole
{
    const INDEX = [
        URI => '/',
        VIEW => 'admin-views.custom-role.create'
    ];

    const ADD = [
        URI => 'create',
        VIEW => 'admin-views.custom-role.index'
    ];

    const EDIT = [
        URI => 'edit/{id}',
        VIEW => 'admin-views.custom-role.edit'
    ];

    const UPDATE = [
        URI => 'update/{id}',
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

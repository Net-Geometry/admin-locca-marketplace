<?php

namespace App\Enums\ViewPaths\Admin;

enum Unit
{
    const INDEX = [
        URI => '/',
        VIEW => 'admin-views.unit.index'
    ];

    const ADD = [
        URI => 'store',
        VIEW => 'admin-views.unit.index'
    ];

    const UPDATE = [
        URI => 'edit/{id}',
        VIEW => 'admin-views.unit.edit'
    ];

    const SEARCH = [
        URI => 'unit/search',
        VIEW => 'admin-views.unit.partials._table'
    ];

    const DELETE = [
        URI => 'delete/{id}',
        VIEW => ''
    ];

    const EXPORT = [
        URI => 'unit/export/{type}',
        VIEW => ''
    ];
}

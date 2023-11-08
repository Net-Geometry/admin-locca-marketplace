<?php

namespace App\Enums\ViewPaths\Admin;

enum Unit
{
    const INDEX = [
        'uri' => '/',
        'view' => 'admin-views.unit.index'
    ];

    const ADD = [
        'uri' => 'store',
        'view' => 'admin-views.unit.index'
    ];

    const UPDATE = [
        'uri' => 'edit/{id}',
        'view' => 'admin-views.unit.edit'
    ];

    const SEARCH = [
        'uri' => 'unit/search',
        'view' => 'admin-views.unit.partials._table'
    ];

    const DELETE = [
        'uri' => 'delete/{id}',
        'view' => ''
    ];

    const EXPORT = [
        'uri' => 'unit/export/{type}',
        'view' => ''
    ];
}

<?php

namespace App\Enums\ViewPaths\Admin;

enum Module
{
    const SHOW = [
        URI => 'show/{id}',
        VIEW => ''
    ];

    const TYPE = [
        URI => 'type',
        VIEW => ''
    ];

    const INDEX = [
        URI => '/',
        VIEW => 'admin-views.module.index'
    ];

    const ADD = [
        URI => 'store',
        VIEW => 'admin-views.module.create'
    ];

    const UPDATE = [
        URI => 'edit/{id}',
        VIEW => 'admin-views.module.edit'
    ];

    const DELETE = [
        URI => 'delete/{id}',
        VIEW => ''
    ];

    const STATUS = [
        URI => 'status/{id}/{status}',
        VIEW => ''
    ];

    const EXPORT = [
        URI => 'export',
        VIEW => ''
    ];

    const SEARCH = [
        URI => 'search',
        VIEW => 'admin-views.module.partials._table'
    ];
}

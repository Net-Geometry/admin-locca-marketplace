<?php

namespace App\Enums\ViewPaths\Admin;

enum Employee
{
    const INDEX = [
        URI => '/',
        VIEW => 'admin-views.employee.list'
    ];

    const ADD = [
        URI => 'store',
        VIEW => 'admin-views.employee.add-new'
    ];

    const UPDATE = [
        URI => 'edit/{id}',
        VIEW => 'admin-views.employee.edit'
    ];

    const DELETE = [
        URI => 'delete/{id}',
        VIEW => ''
    ];
    const SEARCH = [
        URI => 'search',
        VIEW => 'admin-views.employee.partials._table'
    ];
}

<?php

namespace App\Enums\ViewPaths\Admin;

enum Banner
{
    const INDEX = [
        URI => '/',
        VIEW => 'admin-views.banner.index'
    ];

    const ADD = [
        URI => 'store',
        VIEW => 'admin-views.banner.index'
    ];

    const UPDATE = [
        URI => 'edit/{id}',
        VIEW => 'admin-views.banner.edit'
    ];

    const DELETE = [
        URI => 'delete/{id}',
        VIEW => ''
    ];

    const UPDATE_STATUS = [
        URI => 'status/{id}/{status}',
        VIEW => ''
    ];

    const UPDATE_FEATURED = [
        URI => 'featured/{id}/{status}',
        VIEW => ''
    ];

    const SEARCH = [
        URI => 'search',
        VIEW => 'admin-views.banner.partials._table'
    ];

}

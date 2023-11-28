<?php

namespace App\Enums\ViewPaths\Admin;

enum DeliveryMan
{
    const INDEX = [
        URI => '/',
        VIEW => 'admin-views.delivery-man.list'
    ];

    const NEW = [
        URI => '/',
        VIEW => 'admin-views.delivery-man.new'
    ];

    const DENY = [
        URI => '/',
        VIEW => 'admin-views.delivery-man.deny'
    ];

    const ADD = [
        URI => 'store',
        VIEW => 'admin-views.delivery-man.index'
    ];

    const UPDATE = [
        URI => 'edit',
        VIEW => 'admin-views.delivery-man.edit'
    ];

    const DELETE = [
        URI => 'delete',
        VIEW => ''
    ];

    const UPDATE_STATUS = [
        URI => 'status',
        VIEW => ''
    ];

    const VIEW = [
        URI => 'view',
        VIEW => 'admin-views.delivery-man.view'
    ];

    const SEARCH = [
        URI => 'search',
        VIEW => 'admin-views.delivery-man.partials._table'
    ];

}

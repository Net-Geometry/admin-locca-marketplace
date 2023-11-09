<?php

namespace App\Enums\ViewPaths\Admin;

enum Coupon
{
    const INDEX = [
        URI => '/',
        VIEW => 'admin-views.coupon.index'
    ];

    const ADD = [
        URI => 'store',
        VIEW => 'admin-views.coupon.index'
    ];

    const UPDATE = [
        URI => 'edit/{id}',
        VIEW => 'admin-views.coupon.edit'
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
}

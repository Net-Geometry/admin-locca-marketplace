<?php

namespace App\Enums\ViewPaths\Admin;

enum Notification
{
    const INDEX = [
        URI => '/',
        VIEW => 'admin-views.notification.index'
    ];

    const ADD = [
        URI => 'store',
        VIEW => 'admin-views.notification.index'
    ];

    const UPDATE = [
        URI => 'edit/{id}',
        VIEW => 'admin-views.notification.edit'
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

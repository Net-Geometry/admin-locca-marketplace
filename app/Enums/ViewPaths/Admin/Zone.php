<?php

namespace App\Enums\ViewPaths\Admin;

enum Zone
{
    const INDEX = [
        URI => '/',
        VIEW => 'admin-views.zone.index'
    ];

    const ADD = [
        URI => 'store',
        VIEW => 'admin-views.zone.index'
    ];

    const UPDATE = [
        URI => 'edit/{id}',
        VIEW => 'admin-views.zone.edit'
    ];

    const DELETE = [
        URI => 'delete/{id}',
        VIEW => ''
    ];

    const EXPORT = [
        URI => 'export/{type}',
        VIEW => ''
    ];

    const LATEST_MODULE_SETUP = [
        URI => 'module-setup/{id?}',
        VIEW => 'admin-views.zone.module-setup'
    ];

    const MODULE_SETUP = [
        URI => 'module-setup',
        VIEW => 'admin-views.zone.module-setup'
    ];

    const STATUS = [
        URI => 'status/{id}/{status}',
        VIEW => ''
    ];

    const DIGITAL_PAYMENT = [
        URI => 'digital-payment/{id}/{digital_payment}',
        VIEW => ''
    ];

    const CASH_ON_DELIVERY = [
        URI => 'cash-on-delivery/{id}/{cash_on_delivery}',
        VIEW => ''
    ];

    const OFFLINE_PAYMENT = [
        URI => 'offline-payment/{id}/{offline_payment}',
        VIEW => ''
    ];

    const INSTRUCTION = [
        URI => 'instruction',
        VIEW => ''
    ];

    const ZONE_FILTER = [
        URI => 'zone-filter/{id}',
        VIEW => ''
    ];

    const MODULE_UPDATE = [
        URI => 'module-update/{id}',
        VIEW => ''
    ];

    const GET_COORDINATES = [
        URI => 'zone/get-coordinates/{id}',
        VIEW => ''
    ];

    const GET_ALL_ZONE_COORDINATES = [
        URI => 'get-all-zone-cordinates/{id?}',
        VIEW => ''
    ];
}

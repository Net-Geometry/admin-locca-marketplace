<?php

namespace App\Enums\ViewPaths\Admin;

enum Zone
{
    const INDEX = [
        'uri' => '/',
        'view' => 'admin-views.zone.index'
    ];

    const ADD = [
        'uri' => 'store',
        'view' => 'admin-views.zone.index'
    ];

    const UPDATE = [
        'uri' => 'edit/{id}',
        'view' => 'admin-views.zone.edit'
    ];

    const DELETE = [
        'uri' => 'delete/{id}',
        'view' => ''
    ];

    const EXPORT = [
        'uri' => 'export/{type}',
        'view' => ''
    ];

    const LATEST_MODULE_SETUP = [
        'uri' => 'module-setup/{id?}',
        'view' => 'admin-views.zone.module-setup'
    ];

    const MODULE_SETUP = [
        'uri' => 'module-setup',
        'view' => 'admin-views.zone.module-setup'
    ];

    const STATUS = [
        'uri' => 'status/{id}/{status}',
        'view' => ''
    ];

    const DIGITAL_PAYMENT = [
        'uri' => 'digital-payment/{id}/{digital_payment}',
        'view' => ''
    ];

    const CASH_ON_DELIVERY = [
        'uri' => 'cash-on-delivery/{id}/{cash_on_delivery}',
        'view' => ''
    ];

    const OFFLINE_PAYMENT = [
        'uri' => 'offline-payment/{id}/{offline_payment}',
        'view' => ''
    ];

    const INSTRUCTION = [
        'uri' => 'instruction',
        'view' => ''
    ];

    const ZONE_FILTER = [
        'uri' => 'zone-filter/{id}',
        'view' => ''
    ];

    const MODULE_UPDATE = [
        'uri' => 'module-update/{id}',
        'view' => ''
    ];

    const GET_COORDINATES = [
        'uri' => 'zone/get-coordinates/{id}',
        'view' => ''
    ];

    const GET_ALL_ZONE_COORDINATES = [
        'uri' => 'get-all-zone-cordinates/{id?}',
        'view' => ''
    ];
}

<?php

namespace App\Enums\ViewPaths\Admin;

enum Addon
{
    const INDEX = [
        'uri' => '/',
        'view' => 'admin-views.addon.index'
    ];

    const ADD = [
        'uri' => 'store',
        'view' => 'admin-views.addon.index'
    ];

    const UPDATE = [
        'uri' => 'edit/{id}',
        'view' => 'admin-views.addon.edit'
    ];

    const DELETE = [
        'uri' => 'delete/{id}',
        'view' => ''
    ];

    const EXPORT = [
        'uri' => 'export',
        'view' => ''
    ];

    const UPDATE_STATUS = [
        'uri' => 'status/{id}/{status}',
        'view' => ''
    ];

    const BULK_IMPORT = [
        'uri' => 'bulk-import',
        'view' => 'admin-views.addon.bulk-import'
    ];

    const BULK_UPDATE = [
        'uri' => 'bulk-update',
        'view' => 'admin-views.addon.bulk-import'
    ];

    const BULK_EXPORT = [
        'uri' => 'bulk-export',
        'view' => 'admin-views.addon.bulk-export'
    ];
}

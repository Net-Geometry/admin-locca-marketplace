<?php

namespace App\Enums\ViewPaths\Admin;

enum Attribute
{
    const INDEX = [
        'uri' => '/',
        'view' => 'admin-views.attribute.index'
    ];

    const ADD = [
        'uri' => 'store',
        'view' => 'admin-views.attribute.index'
    ];

    const UPDATE = [
        'uri' => 'edit/{id}',
        'view' => 'admin-views.attribute.edit'
    ];

    const DELETE = [
        'uri' => 'delete/{id}',
        'view' => ''
    ];

    const EXPORT = [
        'uri' => 'export-attributes',
        'view' => ''
    ];
}

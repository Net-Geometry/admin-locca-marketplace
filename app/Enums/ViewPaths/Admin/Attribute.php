<?php

namespace App\Enums\ViewPaths\Admin;

enum Attribute
{
    const INDEX = [
        URI => '/',
        VIEW => 'admin-views.attribute.index'
    ];

    const ADD = [
        URI => 'store',
        VIEW => 'admin-views.attribute.index'
    ];

    const UPDATE = [
        URI => 'edit/{id}',
        VIEW => 'admin-views.attribute.edit'
    ];

    const DELETE = [
        URI => 'delete/{id}',
        VIEW => ''
    ];

    const EXPORT = [
        URI => 'export-attributes',
        VIEW => ''
    ];
}

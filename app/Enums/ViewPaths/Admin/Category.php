<?php

namespace App\Enums\ViewPaths\Admin;

enum Category
{
    const INDEX = [
        'uri' => 'view',
        'view' => 'admin-views.category.index'
    ];

    const SUB_CATEGORY_INDEX = [
        'uri' => 'view',
        'view' => 'admin-views.category.sub-index'
    ];

    const LIST = [
        'uri' => 'view',
        'view' => 'admin-views.category.view'
    ];
    const ADD = [
        'uri' => 'add-new',
        'view' => 'admin-views.brand.add-new'
    ];
    const UPDATE = [
        'uri' => 'update/{id}',
        'view' => 'admin-views.category.edit'
    ];
    const DELETE = [
        'uri' => 'delete',
        'view' => ''
    ];
    const STATUS = [
        'uri' => 'status',
        'view' => ''
    ];

    const BULK_IMPORT = [
        'uri' => 'bulk-import',
        'view' => 'admin-views.category.bulk-import'
    ];

    const BULK_EXPORT = [
        'uri' => 'bulk-export',
        'view' => 'admin-views.category.bulk-export'
    ];

}

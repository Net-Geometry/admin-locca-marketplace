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

    const NAME_LIST = [
        'uri' => 'get-all',
        'view' => ''
    ];

    const ADD = [
        'uri' => 'add/{position?}',
        'view' => 'admin-views.category.index'
    ];

    const UPDATE = [
        'uri' => 'update/{id}',
        'view' => 'admin-views.category.edit'
    ];

    const DELETE = [
        'uri' => 'delete/{id}',
        'view' => ''
    ];

    const PRIORITY = [
        'uri' => 'update-priority/{category}',
        'view' => ''
    ];

    const STATUS = [
        'uri' => 'status/{id}/{status}',
        'view' => ''
    ];

    const FEATURED = [
        'uri' => 'featured/{id}/{featured}',
        'view' => ''
    ];

    const EXPORT = [
        'uri' => 'export-categories',
        'view' => ''
    ];

    const BULK_IMPORT = [
        'uri' => 'bulk-import',
        'view' => 'admin-views.category.bulk-import'
    ];

    const BULK_UPDATE = [
        'uri' => 'bulk-update',
        'view' => ''
    ];

    const BULK_EXPORT = [
        'uri' => 'bulk-export',
        'view' => 'admin-views.category.bulk-export'
    ];

}

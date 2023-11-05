<?php

namespace App\Services;

use App\Enums\ViewPaths\Admin\Category as CategoryViewPath;

class CategoryService
{
    public function getViewByPosition(int $position): string
    {
        return match ($position) {
            1 => CategoryViewPath::SUB_CATEGORY_INDEX['view'],
            default => CategoryViewPath::INDEX['view'],
        };
    }
}

<?php

namespace App\Services;

use App\CentralLogics\Helpers;
use App\Enums\ViewPaths\Admin\Category as CategoryViewPath;
use App\Traits\FileManagerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class CategoryService
{
    use FileManagerTrait;

    public function getViewByPosition(int $position): string
    {
        return match ($position) {
            1 => CategoryViewPath::SUB_CATEGORY_INDEX['view'],
            default => CategoryViewPath::INDEX['view'],
        };
    }

    public function getAddData(Request $request, string|int $parentModuleId): array
    {
        return [
            'name' => $request->name[array_search('default', $request->lang)],
            'image' => $this->upload('category/', 'png', $request->file('image')),
            'parent_id' => $request->parent_id == null ? 0 : $request->parent_id,
            'position' => $request->position,
            'module_id' => isset($request->parent_id) ? $parentModuleId : Config::get('module.current_module_id')
        ];
    }

    public function getUpdateData(Request $request, object $object): array
    {
        $slug = Str::slug($request->name[array_search('default', $request->lang)]);
        return [
            'slug' => $object->slug ? $object->slug : "{$slug}{$object->id}",
            'name' => $request->name[array_search('default', $request->lang)],
            'image' => $request->has('image') ? Helpers::update('category/', $object->image, 'png', $request->file('image')) : $object->image,
        ];
    }
}

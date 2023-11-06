<?php

namespace App\Services;

use App\CentralLogics\Helpers;
use App\Enums\ViewPaths\Admin\Category as CategoryViewPath;
use App\Http\Requests\Admin\CategoryAddRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use App\Traits\FileManagerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

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

    public function getAddData(CategoryAddRequest $request, string|int $parentModuleId): array
    {
        return [
            'name' => $request->name[array_search('default', $request->lang)],
            'image' => $this->upload('category/', 'png', $request->file('image')),
            'parent_id' => $request->parent_id == null ? 0 : $request->parent_id,
            'position' => $request->position,
            'module_id' => isset($request->parent_id) ? $parentModuleId : Config::get('module.current_module_id')
        ];
    }

    public function getUpdateData(CategoryUpdateRequest $request, object $object): array
    {
        $slug = Str::slug($request->name[array_search('default', $request->lang)]);
        return [
            'slug' => $object->slug ?? "{$slug}{$object->id}",
            'name' => $request->name[array_search('default', $request->lang)],
            'image' => $request->has('image') ? Helpers::update('category/', $object->image, 'png', $request->file('image')) : $object->image,
        ];
    }

    public function getImportData(Request $request, bool $toAdd = true): array
    {
        try {
            $collections = (new FastExcel)->import($request->file('products_file'));
        } catch (\Exception) {
            return ['flag' => 'wrong_format'];
        }
        $module_id = Config::get('module.current_module_id');

        $data = [];
        foreach ($collections as $collection) {
            if ($collection['Name'] === "") {
                return ['flag' => 'required_fields'];
            }
            $parent_id = is_numeric($collection['ParentId']) ? $collection['ParentId'] : 0;
            $array = [
                'name' => $collection['Name'],
                'image' => $collection['Image'],
                'parent_id' => $parent_id,
                'module_id' => $module_id,
                'position' => $collection['Position'],
                'priority' => is_numeric($collection['Priority']) ? $collection['Priority'] : 0,
                'status' => $collection['Status'] == 'active' ? 1 : 0,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $data[] = !$toAdd ? ($array['id'] = $collection['Id']) : $array;
        }

        return $data;
    }

    public function processExportData(object $collection): array
    {
        $data = [];
        foreach($collection as $item){
            $data[] = [
                'Id'=>$item->id,
                'Name'=>$item->name,
                'Image'=>$item->image,
                'ParentId'=>$item->parent_id,
                'Position'=>$item->position,
                'Priority'=>$item->priority,
                'Status'=>$item->status == 1 ? 'active' : 'inactive',
            ];
        }
        return $data;
    }
}

<?php

namespace App\Repositories;

use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Models\Category;
use App\Traits\FileManagerTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Config;

class CategoryRepository implements CategoryRepositoryInterface
{
    use FileManagerTrait;

    public function __construct(protected Category $category)
    {
    }

    public function add(array $data): string|object
    {
        $category = $this->category->newInstance();
        foreach ($data as $key => $column) {
            $category[$key] = $column;
        }
        $category->save();
        return $category;
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->category->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        // TODO: Implement getList() method.
    }

    public function getListWhere(string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $key = explode(' ', $searchValue);
        return $this->category->with($relations)->where($filters)->module(Config::get('module.current_module_id'))
            ->when(isset($key), function ($query) use ($key) {
                $query->where(function ($query) use ($key) {
                    foreach ($key as $value) {
                        $query->orWhere('name', 'like', "%{$value}%");
                    }
                });
            })->latest()->paginate($dataLimit);
    }

    public function update(string $id, array $data): bool
    {
        // TODO: Implement update() method.
    }

    public function delete(string $id): bool
    {
        // TODO: Implement delete() method.
    }
}

<?php

namespace App\Repositories;

use App\Contracts\Repositories\EmployeeRepositoryInterface;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function __construct(protected Admin $employee)
    {
    }

    public function add(array $data): string|object
    {
        $employee = $this->employee->newInstance();
        foreach ($data as $key => $column) {
            $employee[$key] = $column;
        }
        $employee->save();
        return $employee;
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->employee->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->employee->get();
    }

    public function getListWhere(string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->employee->whereNotIn('id',[1])->latest()->paginate($dataLimit);
    }

    public function update(string $id, array $data): bool|string|object
    {
        $employee = $this->employee->find($id);
        foreach ($data as $key => $column) {
            $employee[$key] = $column;
        }
        $employee->save();
        return $employee;
    }

    public function delete(string $id): bool
    {
        $employee = $this->employee->find($id);
        $employee->translations()->delete();
        $employee->delete();

        return true;
    }

    public function getSearchList(Request $request): Collection
    {
        $key = explode(' ', $request['search']);
        return $this->employee->where('id','!=','1')
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            })->latest()->limit(50)->get();
    }

    public function getFirstWithoutGlobalScopeWhere(array $params, array $relations = []): ?Model
    {
        return $this->employee->withoutGlobalScope('translate')->where($params)->first(['id','name','modules']);
    }
}

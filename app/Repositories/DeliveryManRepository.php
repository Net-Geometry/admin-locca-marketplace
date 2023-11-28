<?php

namespace App\Repositories;

use App\Contracts\Repositories\DeliveryManRepositoryInterface;
use App\Models\DeliveryMan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class DeliveryManRepository implements DeliveryManRepositoryInterface
{
    public function __construct(protected DeliveryMan $dm)
    {
    }

    public function add(array $data): string|object
    {
        $dm = $this->dm->newInstance();
        foreach ($data as $key => $column) {
            $dm[$key] = $column;
        }
        $dm->save();
        return $dm;
    }

    public function getFirstWhere(array $params, array $relations = []): ?Model
    {
        return $this->dm->where($params)->first();
    }

    public function getList(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        return $this->dm->paginate($dataLimit);
    }

    public function getListWhere(string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $key = explode(' ', $searchValue);
        return $this->dm->with($relations)->where($filters)
            ->when(isset($key), function($q) use($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('f_name', 'like', "%{$value}%")
                            ->orWhere('l_name', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('identity_number', 'like', "%{$value}%");
                    }
                });
            })
            ->latest()->paginate($dataLimit);
    }

    public function update(string $id, array $data): bool|string|object
    {
        $dm = $this->dm->find($id);
        foreach ($data as $key => $column) {
            $dm[$key] = $column;
        }
        $dm->save();
        return $dm;
    }

    public function delete(string $id): bool
    {
        $dm = $this->dm->find($id);
        $dm->translations()->delete();
        $dm->delete();

        return true;
    }

    public function getFirstWithoutGlobalScopeWhere(array $params, array $relations = []): ?Model
    {
        return $this->dm->withoutGlobalScope('translate')->where($params)->first();
    }

    public function getZoneWiseListWhere(string $zoneId = 'all',string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator
    {
        $key = explode(' ', $searchValue);
        return $this->dm->with($relations)->where($filters)
            ->when(is_numeric($zoneId), function($query) use($zoneId){
                return $query->where('zone_id', $zoneId);
            })
            ->when(isset($key), function($q) use($key){
                $q->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('f_name', 'like', "%{$value}%")
                            ->orWhere('l_name', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('identity_number', 'like', "%{$value}%");
                    }
                });
            })
            ->latest()->paginate($dataLimit);
    }

}

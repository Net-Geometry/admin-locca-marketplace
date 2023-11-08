<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface ZoneRepositoryInterface extends RepositoryInterface
{
    /**
     * @param array $params
     * @param array $relations
     * @return Model|null
     */
    public function getFirstWithoutGlobalScopeWhere(array $params, array $relations = []): ?Model;

    public function getAll(): Collection;

    public function getWithCoordinateWhere(array $params): ?Model;

    public function getExportList(Request $request): Collection;
    public function getLatest(array $relations = []): ?Model;
    public function zoneModuleSetupUpdate(string $id, array $data, array $moduleData): bool|string|object;

    public function getWithCountLatest(array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;
}

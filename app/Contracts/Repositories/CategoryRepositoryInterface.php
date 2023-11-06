<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    /**
     * @param array $data
     * @return void
     */
    public function addByChunk(array $data): void;

    /**
     * @param array $data
     * @return void
     */
    public function updateByChunk(array $data): void;

    /**
     * @param Request $request
     * @return Collection
     */
    public function getBulkExportList(Request $request): Collection;

    /**
     * @param Request $request
     * @return Collection
     */
    public function getExportList(Request $request): Collection;

    /**
     * @param array $params
     * @param array $relations
     * @return Model|null
     */
    public function getFirstWithoutGlobalscopeWhere(array $params, array $relations = []): ?Model;

    /**
     * @param Request $request
     * @param int|string $dataLimit
     * @return Collection|LengthAwarePaginator
     */
    public function getListOfNames(Request $request, int|string $dataLimit = DEFAULT_DATA_LIMIT): Collection|LengthAwarePaginator;
}

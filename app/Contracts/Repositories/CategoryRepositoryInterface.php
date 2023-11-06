<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    public function addByChunk(array $data): void;

    public function updateByChunk(array $data): void;

    public function getBulkExportList(Request $request): Collection;

    public function getExportList(Request $request): Collection;

    public function getFirstWithoutGlobalscopeWhere(array $params, array $relations = []): ?Model;

    public function getListOfNames(Request $request, int|string $dataLimit = DEFAULT_DATA_LIMIT): Collection|LengthAwarePaginator;
}

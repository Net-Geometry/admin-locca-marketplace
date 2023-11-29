<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface ConversationRepositoryInterface extends RepositoryInterface
{
    public function getFirstWhereWithScope(array $params, array $relations = [], array $scopes=[]): ?Model;
    public function getListWithScope(array $orderBy = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null, array $scopes=[]): Collection|LengthAwarePaginator;
    public function getListWhereWithScope(string $searchValue = null, array $filters = [], array $relations = [], int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null, array $scopes=[]): Collection|LengthAwarePaginator;

    public function getDmConversationList(Request $request, int|string $dataLimit = DEFAULT_DATA_LIMIT, int $offset = null): Collection|LengthAwarePaginator;
}

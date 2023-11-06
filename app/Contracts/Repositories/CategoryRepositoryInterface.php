<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;

interface CategoryRepositoryInterface
{
    public function getFirstWithoutGlobalscopeWhere(array $params, array $relations = []): ?Model;
}

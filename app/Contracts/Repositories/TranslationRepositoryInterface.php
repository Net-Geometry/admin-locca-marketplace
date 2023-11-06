<?php

namespace App\Contracts\Repositories;

use Illuminate\Http\Request;

interface TranslationRepositoryInterface extends RepositoryInterface
{
    public function addByModel(Request $request, object $model, string $modelPath): bool;
}

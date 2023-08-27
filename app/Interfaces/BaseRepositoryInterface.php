<?php

namespace App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface BaseRepositoryInterface
{
    public function getAll(): LengthAwarePaginator;

    public function remove(int $id): void;

    public function edit(int $id, array $details): void;

    public function create(array $details): Model;
}

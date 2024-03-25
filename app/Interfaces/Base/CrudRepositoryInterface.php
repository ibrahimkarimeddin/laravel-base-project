<?php

namespace App\Interfaces\Base;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface CrudRepositoryInterface
{
    public function create($data): Model;

    public function edit(int $id, $data): Model;

    public function delete(int $id): bool;

    public function getAll(bool $is_pagination, int $perPage = 8, ?string $search = null);

    public function updateStatus(int $id, bool $newStatus, $status_column_name): bool;

    public function findByID(int $id): Model | null;
}

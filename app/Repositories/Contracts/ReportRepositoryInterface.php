<?php

namespace App\Repositories\Contracts;

use App\Models\Report;
use Illuminate\Database\Eloquent\Collection;

interface ReportRepositoryInterface
{
    public function allWithRelations(): Collection;
    public function create(array $data): Report;
    public function update(int $id, array $data): ?Report;
    public function find(int $id): ?Report;
    public function getByStatus(string $status): Collection;
    public function getByUser(int $userId): Collection;
}

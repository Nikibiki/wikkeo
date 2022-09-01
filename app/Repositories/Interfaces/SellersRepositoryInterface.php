<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface SellersRepositoryInterface
{
    public function getAll(): Collection;
    public function getAllWithout(array $ids = []): Collection;
    public function getItem(int $id): Collection;
    public function getLastId(): int;
    public function getItemByEmail(string $email): ?array;
    public function getItemByUsername(string $username): ?array;
}

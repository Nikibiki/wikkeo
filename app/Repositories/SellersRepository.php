<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class SellersRepository implements Interfaces\SellersRepositoryInterface
{
    const DATA_PATH = 'sellers.json';

    public function getAll(): Collection
    {
        return (Storage::disk('data')->exists(self::DATA_PATH)) ?
            collect(json_decode(Storage::disk('data')->get(self::DATA_PATH), true))
            : collect([]);
    }

    public function getAllWithout(array $ids = []): Collection
    {
        return $this->getAll()->whereNotIn('id', $ids);
    }

    public function getItem(int $id): Collection
    {
        $item = $this->getAll()->first(fn($item) => $item['id'] == $id);
        return  $item ? collect($item) : collect([]);
    }

    public function getItemByEmail(string $email): ?array
    {
        return $this->getAll()->first(fn($item) => $item['email'] == $email);
    }

    public function getItemByUsername(string $username): ?array
    {
        return $this->getAll()->first(fn($item) => $item['username'] == $username);
    }

    public function getLastId(): int
    {
        return $this->getAll()->max('id');
    }
}

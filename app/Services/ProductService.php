<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    /**
     * @param  string  $name
     * @return Collection|array
     */
    public function searchByName(string $name): Collection|array
    {
        if (! trim($name)) {
            return [];
        }

        return Product::getByName($name);
    }

    /**
     * @param  array  $data
     * @return bool
     */
    public function store(array $data): bool
    {
        return Product::create($data) instanceof Product;
    }
}

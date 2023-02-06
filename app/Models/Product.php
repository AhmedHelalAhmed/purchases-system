<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    const SEARCH_LIMIT_SIZE = 5;
    protected $fillable = ['name'];
    use HasFactory, SoftDeletes;

    public function purchases()
    {
        return $this->belongsToMany(Purchase::class)
            ->withPivot(['quantity','unit_price'])
            ->withTimestamps();
    }

    public static function getByName(string $searchTerm): Collection
    {
        return self::query()
            ->select('name', 'id')
            ->where('name', 'like', $searchTerm . '%')
            ->limit(self::SEARCH_LIMIT_SIZE)
            ->get();
    }
}

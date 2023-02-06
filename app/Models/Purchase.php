<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class Purchase extends Model
{
    use HasFactory;

    const NUMBER_OF_DIGIT_TO_STORE = 100;// 100 means round up to 2 numbers
    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'total',
        'code'
    ];

    public static function getNewCode(): int
    {
        return self::latest()->first()?->code + 1;
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['quantity', 'unit_price'])
            ->withTimestamps();
    }

    /**
     * @return LengthAwarePaginator
     */
    public static function getListPaginated(): LengthAwarePaginator
    {
        return self::select([
            'id',
            'code',
            'user_id',
            'created_at',
            'updated_at',
        ])
            ->selectRaw('round(total/100,2) as total')
            ->with('user:id,name')
            ->paginate();
    }

    /**
     * @return Attribute
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->diffForHumans()
        );
    }

    /**
     * @return Attribute
     */
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Carbon::parse($value)->diffForHumans()
        );
    }
}

<?php

namespace App\Models;

use App\Enums\RolesEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $perPage = 15;

    const EMPTY = '-';

    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'originalRole',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * Interact with role.
     *
     * @return  Attribute
     */
    protected function role(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => RolesEnum::getRoleName($value),
        );
    }

    /**
     * Interact with mobiles.
     *
     * @return  Attribute
     */
    protected function mobile(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ?? self::EMPTY
        );
    }

    /**
     * Interact with the user's address.
     *
     * @return  Attribute
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->diffForHumans()
        );
    }

    /**
     * @return int
     */
    public function getOriginalRoleAttribute(): int
    {
        return $this->attributes['role'];
    }

    public function scopeNotMe($query)
    {
        return $query->where('id', '!=', auth()->id());
    }

    /**
     * @return LengthAwarePaginator
     */
    public static function getListPaginated(): LengthAwarePaginator
    {
        return User::select([
            'id',
            'name',
            'username',
            'created_at',
            'role',
        ])->notMe()
            ->with('profile.nationality')
            ->paginate();
    }
}

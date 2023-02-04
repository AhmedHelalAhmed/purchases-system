<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum RolesEnum: int
{
    use EnumToArray;

    case ADMIN = 1;
    case CUSTOMER = 2;

    /**
     * @param  int  $role
     * @return string
     */
    public static function getRoleName(int $role): string
    {
        return [
            self::ADMIN->value => 'Admin',
            self::CUSTOMER->value => 'Customer',
        ][$role];
    }

    public static function getOptions(): array
    {
        return [
            [
                'id' => self::ADMIN->value,
                'label' => 'Admin',
            ],
            [
                'id' => self::CUSTOMER->value,
                'label' => 'Customer',
            ],
        ];
    }
}

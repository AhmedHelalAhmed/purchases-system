<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum RolesEnum: int
{
    use EnumToArray;

    case ADMIN = 1;
    case CUSTOMER = 2;

    const ADMIN_ROLE_NAME = 'Admin';

    const CUSTOMER_ROLE_NAME = 'Customer';

    /**
     * @param  int  $role
     * @return string
     */
    public static function getRoleName(int $role): string
    {
        return [
            self::ADMIN->value => self::ADMIN_ROLE_NAME,
            self::CUSTOMER->value => self::CUSTOMER_ROLE_NAME,
        ][$role];
    }

    public static function getOptions(): array
    {
        return [
            [
                'id' => self::ADMIN->value,
                'label' => self::ADMIN_ROLE_NAME,
            ],
            [
                'id' => self::CUSTOMER->value,
                'label' => self::CUSTOMER_ROLE_NAME,
            ],
        ];
    }
}

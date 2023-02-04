<?php

namespace App\Services;

use App\Enums\RolesEnum;
use App\Models\User;

class AdminService extends UserService
{
    public function storeAdmin(array $data): bool
    {
        return boolval($this->storeUser(
            array_merge($data, [
                'role' => RolesEnum::ADMIN->value,
            ])
        ));
    }

    public function updateAdmin(User $user, array $data): bool
    {
        return $this->updateUser($user, $data);
    }
}

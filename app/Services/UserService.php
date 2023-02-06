<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserService implements DeleteUserInterface
{
    public function storeUser(array $data): User
    {
        return User::create(array_merge($data, ['password' => Hash::make($data['password'])]));
    }

    public function updateUser(User $user, array $data): bool
    {
        if ($this->isPasswordChanged($data)) {
            $data = array_merge($data, ['password' => Hash::make($data['password'])]);
        } else {
            unset($data['password']);
        }

        return $user->update($data);
    }

    protected function isPasswordChanged(array $data): bool
    {
        return boolval(Arr::get($data, 'password', false));
    }

    public function getList(): LengthAwarePaginator
    {
        return User::getListPaginated();
    }

    public function prepareData(): array
    {
        return [
            'roles' => \App\Enums\RolesEnum::getOptions(),
            'nationalities' => \App\Models\Nationality::select('id')->selectRaw('name as label')->get()->toArray(),
        ];
    }

    public function editData(User $user): array
    {
        $user->load('profile')->append('originalRole');

        return array_merge($this->prepareData(), [
            'user' => $user,
        ]);
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}

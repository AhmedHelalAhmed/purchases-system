<?php

namespace App\Services;

use App\Enums\RolesEnum;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerService extends UserService
{
    public function storeCustomer(array $data): bool
    {
        try {
            DB::beginTransaction();
            $user = $this->storeUser(
                array_merge($data, [
                    'role' => RolesEnum::CUSTOMER->value,
                ])
            );
            $user->profile()->create($data);
            DB::commit();

            return true;
        } catch (Exception $exception) {
            Log::error('Error: ' . $exception->getMessage(), ['exception' => $exception]);
            DB::rollBack();

            return false;
        }
    }

    public function updateCustomer(User $user, array $data): bool
    {
        try {
            DB::beginTransaction();
            $this->updateUser($user, $data);
            $user->profile->update($data);
            DB::commit();

            return true;
        } catch (Exception $exception) {
            Log::error('Error: ' . $exception->getMessage(), ['exception' => $exception]);
            DB::rollBack();

            return false;
        }
    }

    public function delete(User $user): void
    {
        $user->profile()->delete();
        parent::delete($user);
    }

    public function searchByName(string $name): Collection|array
    {
        if (!trim($name)) {
            return [];
        }
        return User::getCustomersByName($name);
    }
}

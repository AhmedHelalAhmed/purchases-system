<?php

namespace App\Services;

use App\Models\User;

interface DeleteUserInterface
{
    public function delete(User $user): void;
}

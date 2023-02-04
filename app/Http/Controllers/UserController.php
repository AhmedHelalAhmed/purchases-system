<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DeleteUserInterface;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('User/Index', [
            'users' => app(UserService::class)->getList(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('User/Create',
            app(UserService::class)->prepareData());
    }

    public function edit(User $user): Response
    {
        return Inertia::render('User/Edit',
            app(UserService::class)->editData($user));
    }

    public function destroy(User $user): RedirectResponse
    {
        app(DeleteUserInterface::class)->delete($user);

        return redirect()
            ->route('users.index')
            ->with('message', 'User deleted successfully');
    }
}

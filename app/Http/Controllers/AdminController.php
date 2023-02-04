<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminStoreRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    private AdminService $service;

    public function __construct(AdminService $service)
    {
        $this->service = $service;
    }

    public function store(AdminStoreRequest $request): RedirectResponse
    {
        $status = $this->service->storeAdmin($request->validated());

        return redirect()
            ->route('users.index')
            ->with('message', $status ? 'Admin created successfully' : 'Something went wrong try again later');
    }

    /**
     * @param  AdminUpdateRequest  $request
     * @param  User  $user
     * @return RedirectResponse
     */
    public function update(AdminUpdateRequest $request, User $user)
    {
        $status = $this->service->updateAdmin($user, $request->validated());

        return redirect()
            ->route('users.index')
            ->with('message', $status ? 'Admin updated successfully' : 'Something went wrong try again later');
    }
}

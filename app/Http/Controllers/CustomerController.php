<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\User;
use App\Services\CustomerService;
use Illuminate\Http\RedirectResponse;

class CustomerController extends Controller
{
    private CustomerService $service;

    public function __construct(CustomerService $service)
    {
        $this->service = $service;
    }

    public function store(CustomerStoreRequest $request): RedirectResponse
    {
        $status = $this->service->storeCustomer($request->validated());

        return redirect()
            ->route('users.index')
            ->with('message', $status ? 'Customer created successfully' : 'Something went wrong try again later');
    }

    public function update(CustomerUpdateRequest $request, User $user): RedirectResponse
    {
        $status = $this->service->updateCustomer($user, $request->validated());

        return redirect()
            ->route('users.index')
            ->with('message', $status ? 'Customer updated successfully' : 'Something went wrong try again later');
    }
}

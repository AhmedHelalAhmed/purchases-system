<?php

namespace App\Http\Controllers;

use App\Enums\MessagesEnum;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Models\User;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class CustomerController extends Controller
{
    private CustomerService $service;

    /**
     * @param CustomerService $service
     */
    public function __construct(CustomerService $service)
    {
        $this->service = $service;
    }

    /**
     * @param CustomerStoreRequest $request
     * @return RedirectResponse
     */
    public function store(CustomerStoreRequest $request): RedirectResponse
    {
        $status = $this->service->storeCustomer($request->validated());

        return redirect()
            ->route('users.index')
            ->with('message', $status ? 'Customer created successfully' : MessagesEnum::ERROR_MESSAGE->value);
    }

    /**
     * @param CustomerUpdateRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(CustomerUpdateRequest $request, User $user): RedirectResponse
    {
        $status = $this->service->updateCustomer($user, $request->validated());

        return redirect()
            ->route('users.index')
            ->with('message', $status ? 'Customer updated successfully' : MessagesEnum::ERROR_MESSAGE->value);
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'customers' =>   $this->service->searchByName(request('name',''))
        ]);
    }
}

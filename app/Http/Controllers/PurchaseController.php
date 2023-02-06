<?php

namespace App\Http\Controllers;

use App\Enums\MessagesEnum;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;
use App\Models\Purchase;
use App\Services\PurchaseService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PurchaseController extends Controller
{
    private PurchaseService $service;

    /**
     * @param  PurchaseService  $service
     */
    public function __construct(PurchaseService $service)
    {
        $this->service = $service;
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Purchase/Index', [
            'purchases' => $this->service->getList(),
        ]);
    }

    /**
     * @param  StorePurchaseRequest  $request
     * @return RedirectResponse
     */
    public function store(StorePurchaseRequest $request): RedirectResponse
    {
        $status = $this->service->storePurchase($request->validated());
        if ($status) {
            return redirect()
                ->route('purchases.index')
                ->with('message', 'Purchase created successfully');
        }

        return redirect()
            ->back()
            ->with('message', MessagesEnum::ERROR_MESSAGE->value);
    }

    /**
     * @param  Purchase  $purchase
     * @param  UpdatePurchaseRequest  $request
     * @return RedirectResponse
     */
    public function update(Purchase $purchase, UpdatePurchaseRequest $request): RedirectResponse
    {
        $status = $this->service->updatePurchase($purchase, $request->validated());
        if ($status) {
            return redirect()
                ->route('purchases.index')
                ->with('message', 'Purchase updated successfully');
        }

        return redirect()
            ->back()
            ->with('message', MessagesEnum::ERROR_MESSAGE->value);
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('Purchase/Create');
    }

    /**
     * @param  Purchase  $purchase
     * @return Response
     */
    public function edit(Purchase $purchase): Response
    {
        return Inertia::render('Purchase/Edit', $this->service->prepareData($purchase));
    }

    /**
     * @param  Purchase  $purchase
     * @return RedirectResponse
     */
    public function destroy(Purchase $purchase): RedirectResponse
    {
        $status = $this->service->delete($purchase);
        if ($status) {
            return redirect()
                ->route('purchases.index')
                ->with('message', 'Purchase deleted successfully');
        }

        return redirect()
            ->back()
            ->with('message', MessagesEnum::ERROR_MESSAGE->value);
    }

    /**
     * @param  Purchase  $purchase
     * @return Response
     */
    public function show(Purchase $purchase): Response
    {
        return Inertia::render('Purchase/Show', $this->service->prepareData($purchase));
    }
}

<?php

namespace App\Services;

use App\Models\Purchase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseService
{
    /**
     * @return LengthAwarePaginator
     */
    public function getList(): LengthAwarePaginator
    {
        return Purchase::getListPaginated();
    }

    /**
     * @param  array  $data
     * @return bool
     */
    public function storePurchase(array $data): bool
    {
        try {
            DB::beginTransaction();
            $purchase = Purchase::create([
                'code' => Purchase::getNewCode(),
                'user_id' => auth()->user()->isAdmin() ? $data['user_id'] : auth()->id(),
                'total' => $data['total'] * Purchase::NUMBER_OF_DIGIT_TO_STORE,
                'created_by' => auth()->id(),
            ]);
            $purchase->products()->attach($this->getPurchasesProductsFromPurchaseItems($data['purchases_items']));
            DB::commit();

            return true;
        } catch (\Exception $exception) {
            Log::error('error'.$exception->getMessage(), ['exception' => $exception]);
            DB::rollBack();

            return false;
        }
    }

    /**
     * @param  Purchase  $purchase
     * @param  array  $data
     * @return bool
     */
    public function updatePurchase(Purchase $purchase, array $data): bool
    {
        try {
            DB::beginTransaction();
            $purchase->update([
                'user_id' => auth()->user()->isAdmin() ? $data['user_id'] : auth()->id(),
                'total' => request('total') * 100,
            ]);
            $purchase->products()->sync($this->getPurchasesProductsFromPurchaseItems($data['purchases_items']));
            DB::commit();

            return true;
        } catch (\Exception $exception) {
            Log::error('error'.$exception->getMessage(), ['exception' => $exception]);
            DB::rollBack();

            return false;
        }
    }

    /**
     * @param  array  $purchasesItems
     * @return array
     */
    private function getPurchasesProductsFromPurchaseItems(array $purchasesItems): array
    {
        $purchasesProducts = [];
        foreach ($purchasesItems as $purchasesItem) {
            $purchasesProducts[$purchasesItem['product_id']] = [
                'quantity' => $purchasesItem['quantity'] * Purchase::NUMBER_OF_DIGIT_TO_STORE,
                'unit_price' => $purchasesItem['unit_price'] * Purchase::NUMBER_OF_DIGIT_TO_STORE,
            ];
        }

        return $purchasesProducts;
    }

    /**
     * @param  Purchase  $purchase
     * @return array
     */
    public function prepareData(Purchase $purchase): array
    {
        $purchase->load('products');
        $purchase->total /= 100;
        $purchase->purchases_items = $purchase->products->map(fn ($product) => [
            'product_id' => $product->id,
            'quantity' => $product->pivot->quantity / 100,
            'unit_price' => $product->pivot->unit_price / 100,
            'total' => round($product->pivot->quantity / 100 * $product->pivot->unit_price / 100, 2),
        ]);

        return [
            'purchase' => $purchase,
            'customers' => [
                [
                    'id' => $purchase->user->id,
                    'name' => $purchase->user->name,
                ],
            ],
            'products' => $purchase->products->map(fn ($product) => [
                'id' => $product->id,
                'name' => $product->name,
            ])->toArray(),
        ];
    }

    /**
     * @param  Purchase  $purchase
     * @return bool
     */
    public function delete(Purchase $purchase): bool
    {
        try {
            DB::beginTransaction();
            $purchase->products()->detach();
            $purchase->delete();
            DB::commit();

            return true;
        } catch (\Exception $exception) {
            Log::error('error'.$exception->getMessage(), ['exception' => $exception]);
            DB::rollBack();

            return false;
        }
    }
}

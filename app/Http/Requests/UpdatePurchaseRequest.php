<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        if (auth()->user()->isAdmin()) {
            $userIdRules = 'required|exists:users,id';
        } else {
            $userIdRules = 'nullable';
        }

        return [
            'user_id' => $userIdRules,
            'total' => ['required', 'numeric'],
            'purchases_items' => ['required', 'array', 'min:1'],
            'purchases_items.*.product_id' => ['required', 'exists:products,id'],
            'purchases_items.*.quantity' => ['required', 'numeric'],
            'purchases_items.*.unit_price' => ['required', 'numeric'],
        ];
    }
}

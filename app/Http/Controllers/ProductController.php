<?php

namespace App\Http\Controllers;

use App\Enums\MessagesEnum;
use App\Http\Requests\StoreProductRequest;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * @param  StoreProductRequest  $request
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $status = $this->productService->store($request->validated());

        if ($status) {
            return response()->json([
                'message' => 'Product created successfully',
            ]
            );
        }

        return response()->json([
            'message' => MessagesEnum::ERROR_MESSAGE,
        ]
        );
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'products' => $this->productService->searchByName(request('name', '')),
        ]
        );
    }
}

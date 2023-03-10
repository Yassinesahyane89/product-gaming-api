<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Product;
use App\Http\Requests\V1\StoreProductRequest;
use App\Http\Requests\V1\UpdateProductRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductResource::collection(Product::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->all() + ['user_id' => Auth()->user()->id]);
        return response()->json([
            'status' => true,
            'message' => "product Created successfully!",
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $user = Auth()->user();
        if (!$user->can('edit All product')  && $user->id != $product->user_id) {
            return response()->json([
                'status' => false,
                'message' => "You don't have permission to edit this product!",
            ], 200);
        }
        $product->update($request->all());

        if (!$product) {
            return response()->json(['message' => 'product not found'], 404);
        }

        return response()->json([
            'status' => true,
            'message' => "product Updated successfully!",
            'product' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $user = Auth()->user();
        if (!$user->can('edit All product')  && $user->id != $product->user_id) {
            return response()->json([
                'status' => false,
                'message' => "You don't have permission to delete this product!",
            ], 200);
        }
        $product->delete();

        if (!$product) {
            return response()->json([
                'message' => 'product not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'product deleted successfully'
        ], 200);
    }
}

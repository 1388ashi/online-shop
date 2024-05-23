<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Product\Http\Requests\Product\storeRequest;
use Modules\Product\Http\Requests\Product\updateRequest;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Modules\Store\Models\Store;

class ProductController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::query()->whereNull('parent_id')->select(['id','name'])->get();

        $products = Product::query()
        ->with(['category:id,name','specificaions:id,title'])
        ->latest('id')
        ->paginate();

        return response()->success('',compact('products','categories'));
    }
    
    public function show(Product $product): JsonResponse
{
    $product->load([
        'category:id,name',
        'specifications:id,name',
    ]);

    return response()->success("مشخصات محصول {$product->id}", compact('product'));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(storeRequest $request): JsonResponse
    {
        try {
            $product = Product::create($request->validated());
            $product->uploadFiles($request);
            $specifications = $request->specifications;
            foreach($specifications as $specification) {
                $product->specifications()->attach($specification['id'], ['value' => $specification['value']]);
            }
            //انبار
            $store = Store::create([
                'product_id' => $product->id,
                'balance' => $product->quantity
            ]);
            $store->transactions()->create([
            'type' => 'increment',
            'quantity' => $product->quantity,
            'description' => 'افزایش موجودی بعد از ثبت محصول با شناسه ' . $product->id
            ]);
            return response()->success('محصول با موفقیت ثبت شد.');
        } catch (\Exception $e) {
            return response()->error('خطا در ساخت محصول.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(updateRequest $request, Product $product): JsonResponse
    {
        try {
            $product->update($request->validated());
            $product->uploadFiles($request);
            $syncData = [];
            foreach($request->specifications as $specification) {
                $syncData[$specification['id']] = ['value' => $specification['value']];
            }
            $product->specifications()->sync($syncData);

            return response()->success('محصول با موفقیت به روزرسانی شد.');
        } catch (\Exception $e) {
            return response()->error('خطا در ویرایش محصول.');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        if ($product->quantity > 0) {

            return response()->error('محصول موجودی دار حذف نمیشود.');
        }
        $product->delete();

        return response()->success('محصول با موفقیت حذف شد.');
    }
}

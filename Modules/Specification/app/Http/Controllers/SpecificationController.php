<?php

namespace Modules\Specification\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Product\Models\Category;
use Modules\Specification\Http\Requests\StoreRequest;
use Modules\Specification\Models\Specification;

class SpecificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = Category::query()->whereNull('parent_id')->select(['id','name'])->get();
        $specifications = Specification::query()->with('categories:id,name')->latest('id')->paginate();

        return response()->success('',compact('specifications','categories'));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $specification = Specification::query()->create([
            'name' => $request->input('name'),
            'status' => $request->input('status'),
        ]);
        $specification->categories()->attach($request->input('categories'));
        
        return response()->success('مشخصات با موفقیت ثبت شد.');
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(StoreRequest $request, Specification $specification): JsonResponse
    {
        $specification->update([
            'name' => $request->input('name'),
            'status' => $request->input('status'),
        ]);
        $specification->categories()->sync($request->input('categories'));
        
        return response()->success('مشخصات با موفقیت به روزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specification $specification): JsonResponse
    {
        $specification->delete();

        return response()->success('مشخصات با موفقیت حذف شد.');
    }
}

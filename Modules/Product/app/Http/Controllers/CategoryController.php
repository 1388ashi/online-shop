<?php

namespace Modules\Product\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Product\Http\Requests\Category\storeRequest;
use Modules\Product\Http\Requests\Category\updateRequest;
use Modules\Product\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = Category::query()
        ->with(['parent:id,name','recursiveChildren:id,name,parent_id'])
        ->latest('id')
        ->paginate();

        return response()->success('',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeRequest $request): JsonResponse
    {
        $category = Category::query()->create([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id'),
            'featured' => $request->input('featured'),
            'status' => $request->input('status'),
        ]);
        $category->uploadFiles($request);
        return response()->success('دسته بندی با موفقیت ثبت شد.');
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(updateRequest $request, Category $category): JsonResponse
    {
        $category->update([
            'name' => $request->name,
            'parent_id' => $request->input('parent_id'),
            'featured' => $request->input('featured'),
            'status' => $request->input('status'),
        ]);
        $category->uploadFiles($request);

        return response()->success('دسته بندی با موفقیت ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->success('دسته بندی با موفقیت حذف شد.');
    }
}

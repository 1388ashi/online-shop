<?php

namespace Modules\Product\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Product\Models\Category;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::query()
        ->with(['parent:id,name','recursiveChildren:id,name,parent_id'])
        ->latest('id')
        ->get();

        return response()->success('',compact('categories'));
    }
}

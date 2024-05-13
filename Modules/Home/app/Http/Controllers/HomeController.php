<?php

namespace Modules\Home\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Modules\Slider\Models\Slider;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function home(): JsonResponse
    {
        $sliders = Slider::query()->where('status',1)->select('id','link','status')->latest('id')->take(4)->get();
        $lastProducts = Product::query()
        ->select('id', 'title', 'discount', 'discount_type', 'price')
        ->latest('id')
        ->take(10)
        ->get();

        $lastProducts->map(function ($product) {
            return $product->setAttribute('price_with_discount', $product->totalPriceWithDiscount());
        });
        $mostDiscountProducts = Product::getTopDiscountedProducts();

        $mostViewedProducts  = Product::orderByViews()->take(10)->get();

        $bestSellingProducts = DB::table('order_items')
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();
        $productIds = $bestSellingProducts->pluck('product_id');

        $productsMostSales = DB::table('products')
            ->whereIn('id', $productIds)
            ->get();

        return response()->success('',compact('sliders','lastProducts','mostDiscountProducts','mostViewedProducts','productsMostSales'));
    }
}
<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Admin\Models\Admin;
use Modules\Invoice\Models\Invoice;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $invoices = Invoice::where('status',0)->select('id','doctor_id','amount','created_at','status')->latest('id')->take(10)->get();
        $admins = Admin::count();
        $products = Product::count();
        $categories = Category::count();
        // $logActivitys =  Activity::select('description','subject_type','event')->latest('id')->take(8)->get();
        
        return view('admin.pages.dashboard',compact(
            // 'logActivitys',
            'categories'
            ,'products'
            ,'admins'
            // ,'invoices'
        ));
    }
}

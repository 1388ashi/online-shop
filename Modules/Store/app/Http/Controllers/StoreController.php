<?php

namespace Modules\Store\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\Store\Http\Requests\storeRequest;
use Modules\Store\Http\Requests\updateRequest;
use Modules\Store\Models\Store;
use Modules\Store\Models\StoreTransaction;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $stores = Store::query()
        ->select(['id','product_id','balance'])
        ->with('product:id,title')
        ->latest('id')
        ->paginate();
        
        return response()->success('',compact('stores'));
    }
    
    public function show(Store $store): JsonResponse
    {
        $store->load([
            'transactions:id,type,store_id,quantity,description,created_at',
        ]);

        return response()->success("مشاهده انبار {$store->id}",compact('store'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $storeTransaction = StoreTransaction::create($request->validated());
            if ($request->type == 'increment') {
                $storeTransaction->store->increment('balance',$storeTransaction->quantity);
            }else{
                $storeTransaction->store->decrement('balance',$storeTransaction->quantity);
            }
            DB::commit();

            return response()->success('تراکنش با موفقیت ثبت شد.');
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->error('خطا در ساخت تراکنش.');
        }
    }

}

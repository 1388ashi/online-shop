<?php

namespace Modules\Customer\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Customer\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $customers = Customer::query()
        ->select(['id', 'name', 'mobile', 'email', 'national_code', 'status'])
        ->latest('id')
        ->paginate();

        return response()->success('', compact('customers'));
    }

    /**
     * Show the specified resource.
     */
    public function show(Customer $customer): JsonResponse
    {
        if(!empty($customer->addresses)){
            $customer->load([
                'addresses:id,name,mobile,address,postal_code',
            ]);
        }

        return response()->success("مشخصات مشتری {$customer->id}",compact('customer'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        if (empty($customer->orders)) {

            return response()->error('مشتری که سفارش داشته باشد حذف نمیشود.');
        }
        $customer->delete();

        return response()->success('مشتری با موفقیت حذف شد.');
    }
}
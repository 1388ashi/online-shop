<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Http\Requests\Address\storeRequest;
use Modules\Customer\Models\Address;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $addresses = Address::query()
        ->select(['id', 'name', 'mobile', 'address','postal_code'])
        ->get();

        return response()->success('', compact('addresses'));
    }
    public function store(storeRequest $request): JsonResponse
    {
        $customerId =  Auth::guard('customer-api')->user()->id;
        try {
            $address = Address::query()->create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
                'city_id' => $request->city_id,
                'customer_id' => $customerId,
                'status' => $request->status,
            ]);    
            
            return response()->success('آدرس با موفقیت ثبت شد.');
        } catch (\Exception $e) {
            return response()->error('خطا در ساخت آدرس.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(storeRequest $request,Address $address): JsonResponse
    {
        try {
            $address->update([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'postal_code' => $request->postal_code,
                'city_id' => $request->city_id,
                'status' => $request->status,
            ]);  

            return response()->success('آدرس با موفقیت به روزرسانی شد.');
        } catch (\Exception $e) {
            return response()->error('خطا در ویرایش آدرس.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address): JsonResponse
    {
        $address->delete();

        return response()->success('آدرس با موفقیت حذف شد.');
    }
}

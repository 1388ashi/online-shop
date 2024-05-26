<?php

namespace Modules\Customer\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Customer\Http\Requests\ChangePassRequest;
use Modules\Customer\Http\Requests\UpdateRequest;
use Modules\Customer\Models\Customer;

class CustomerController extends Controller
{
    public function profile()
    {
        $customer_id =  Auth::guard('customer-api')->user();
        $customer = Customer::
            where('id',$customer_id->id)
            ->select(['id', 'name', 'mobile', 'email', 'national_code'])
            ->get();
        return response()->success('', compact('customer'));
    }

    public function update(UpdateRequest $request, Customer $customer): JsonResponse
    {
        try {
            $customer->update($request->validated());

            return response()->success('پروفایل با موفقیت به روزرسانی شد.');
        } catch (\Exception $e) {
            
            return response()->error('خطا در ویرایش پروفایل.');
        }
    }
    public function changePassword(ChangePassRequest $request,Customer $customer){
        
        if (!Hash::check($request->old_password,$customer->password)) {
            return response()->error('رمز عبور فعلی اشتباه است');
        }
        $customer->password = Hash::make($request->password);
        $customer->save();
        // $request->user()->logout();
            return response()->success('کلمه عبور با موفقیت به روزرسانی شد.');
    }
}

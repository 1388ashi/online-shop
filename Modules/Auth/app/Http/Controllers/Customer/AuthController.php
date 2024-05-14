<?php

namespace Modules\Auth\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Modules\Auth\Http\Requests\Customer\CustomerStoreRequest;
use Modules\Auth\Http\Requests\Customer\VerifyRequest;
use Modules\Core\App\Helpers\Helpers;
use Modules\Core\App\Rules\IranMobile;
use Modules\Customer\Models\Customer;
use Modules\SmsToken\Models\SmsToken;

class AuthController extends Controller
{
    public function registerLogin(Request $request): JsonResponse {
        $credentials = $request->validate([
            'mobile' => ['required',new IranMobile],
        ]);
        $mobile = $request->mobile;
        $isCustomer = null;
        $query = Customer::where('mobile',$mobile)->exists();
        
        if ($query) {
            $isCustomer = true;
        }else{
            $isCustomer = false;
        }
        return response()->success('این شماره در سایت وجود دارد',compact('isCustomer','mobile'));
    }
    
    public function sendToken(Request $request): JsonResponse {
        $credentials = $request->validate([
            'mobile' => ['required',new IranMobile],
        ]);
        $mobile = $request->mobile;
        $verificationCode = 1234;
        $currentTime = Carbon::now(); 
        $addTime = $currentTime->addMinutes(2);
        
        $smsToken = SmsToken::updateOrCreate(
            ['mobile' => $mobile],
            ['token' => $verificationCode, 'expires_at' => $addTime]
        );
        return response()->success('توکن با موفقیت ارسال شد.');
    }

    public function verify(VerifyRequest $request): JsonResponse{
        try {
            $customer = Customer::query()->where('mobile', $request->mobile)->first();

            $customer->mobile_verified_at = now();
            $customer->save();
            $data['mobile'] = $request->input('mobile');
            
            if ($request->type === 'login') {
                
                $token = $customer->createToken('authToken');
                Sanctum::actingAs($customer);
                
                $data = [
                    'customer' => $customer,
                    'access_token' => $token->plainTextToken,
                    'token_type' => 'Bearer'
                ];
            }

            return response()->success('مشتری با موفقیت راستی آزمایی شد. ', compact('data'));
        } catch(Exception $exception) {
            Log::error($exception->getTraceAsString());
            return response()->error(
                'مشکلی در برنامه بوجود آمده است. لطفا با پشتیبانی تماس بگیرید: ' . $exception->getMessage(),
                500
            );
        }
    }
    public function register(CustomerStoreRequest $request): JsonResponse {
        $customer = Customer::query()->where('mobile', $request->mobile)->first();

        if (empty($customer)) {
            $customer = Customer::query()->create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'status' => $request->status,
                'national_code' => $request->national_code,
                'password' => bcrypt($request->password),
            ]);

            return response()->success('مشتری با موفقیت ثبت نام کرد');
        }else{
            return response()->error('شماره در سایت ثبت نام کرده است'); 
        }
    }
    public function login(Request $request): JsonResponse {
        
        $credentials = $request->validate([
            'mobile' => ['required', 'digits:11',new IranMobile],
            'password' => ['required', 'min:3'],
        ]);
        $mobile = $request->mobile;
        $password = $request->password;
        
        $customer = Customer::query()->where('mobile', $mobile)->first();
        
        if (!$customer || !Hash::check($password, $customer->password)) {
            return response()->error('اطلاعات وارد شده اشتباه است',422);
        }
        $token = $customer->createToken('authToken');
        Sanctum::actingAs($customer);
        
        $data = [
            'customer' => $customer,
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer'
        ];
        
        return response()->success('مشتری با موفقیت وارد شد', compact('data'));
    }
    public function logout(Request $request) {
        if (Auth::guard('customer-api')->check()) {
            $customer = Auth::guard('customer-api')->user();
            $customer->currentAccessToken()->delete();
            return response()->success('مشتری با موفقیت از برنامه خارج شد');
        } else {
            return response()->error('کاربر احراز هویت نشده است.', 401);
        }
    }
}

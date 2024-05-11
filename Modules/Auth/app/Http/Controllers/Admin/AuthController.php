<?php

namespace Modules\Auth\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Modules\Admin\Models\Admin;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse {

        $credentials = $request->validate([
            'mobile' => ['required', 'digits:11'],
            'password' => ['required', 'min:3'],
        ]);
        $mobile = $request->mobile;
        $password = $request->password;
        
        $admin = Admin::query()->where('mobile', $mobile)->first();

        if (!$admin || !Hash::check($password, $admin->password)) {
            return response()->error('اطلاعات وارد شده اشتباه است',422);
        }
        
        $token = $admin->createToken('authToken');
        Sanctum::actingAs($admin);
        
        $data = [
            'admin' => $admin,
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer'
        ];
        
        return response()->success('مدیر با موفقیت وارد شد', compact('data'));
    }
    
    public function logout(Request $request) {

        if (Auth::guard('admin-api')->check()) {
            $admin = Auth::guard('admin-api')->user();
            $admin->currentAccessToken()->delete();
            return response()->success('مدیر با موفقیت از برنامه خارج شد');
        } else {
            return response()->error('کاربر احراز هویت نشده است.', 401);
        }
    }
}

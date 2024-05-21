<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Http\Requests\storeRequest;
use Modules\Admin\Http\Requests\updateRequest;
use Modules\Admin\Models\Admin;

class AdminController extends Controller
{
    public function profile()
    {
        $admin_id =  Auth::guard('admin-api')->user();
        $admin = Admin::
            where('id',$admin_id->id)
            ->select(['id', 'name', 'mobile', 'email'])
            ->get();
        return response()->success('', compact('admin'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $admins = Admin::query()->paginate();

        return response()->success('',compact('admins'));
    }

    public function store(storeRequest $request): JsonResponse
    {
        $admin = Admin::query()->create([
            'name' => $request->input('name'),
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => bcrypt($request->password),
        ]);
        
        $admin->assignRole('admin');
        
        return response()->success('ادمین با موفقیت ثبت شد.');
    }
    
    public function update(updateRequest $request, Admin $admin): JsonResponse
    {
        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => bcrypt($request->password),
        ]);
        
        return response()->success('ادمین با موفقیت به روزرسانی شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin): JsonResponse
    {
        $admin->delete();

        return response()->success('ادمین با موفقیت حذف شد.');
    }
}

<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Http\Requests\StoreRequest;
use Modules\Admin\Http\Requests\UpdateRequest;
use Modules\Admin\Models\Admin;

class AdminController extends Controller
{
    public function profile()
    {
        $admin_id =  Auth::guard('admin-api')->user();
        $admin = Admin::
            where('id',$admin_id->id)
            ->select(['id', 'name', 'mobile', 'email'])
            ->with('roles')
            ->get();
        return response()->success('', compact('admin'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $admins = Admin::query()->with('roles')->paginate();

        return response()->success('',compact('admins'));
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $admin = Admin::query()->create([
            'name' => $request->input('name'),
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => bcrypt($request->password),
        ]);
        
        $admin->assignRole($request->role);
        
        return response()->success('ادمین با موفقیت ثبت شد.');
    }
    
    public function update(UpdateRequest $request, Admin $admin): JsonResponse
    {
         $password = filled($request->password) ? $request->password : $admin->password;
        
        $admin->update([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'password' => bcrypt($password),
            'status' => $request->status 
        ]);
        $admin->assignRole($request->role);
        
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

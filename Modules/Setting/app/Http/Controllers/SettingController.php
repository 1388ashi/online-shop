<?php

namespace Modules\Setting\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\Setting\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function store(Request $request): JsonResponse {

        $setting = new Setting();
        $setting->name = $request->input('name');
        $setting->label = $request->input('label');
        $setting->type = $request->input('type');
        $setting->value = $request->input('value');
        $setting->group = $request->input('group');

        $setting->save();
        
        return response()->success('تنظیمات با موفقیت ثبت شد.');
    }

    public function update(Request $request): JsonResponse {
        $inputs = $request->except(['_token','_method']);
        foreach ($inputs as $name => $value) {
            if ($setting = Setting::where('name',$name)->first()) {
                if ($setting->type == 'file' && $request->file($name)->isValid()) {
                    if ($setting->value) {
                        Storage::disk('public')->delete($setting->value);
                    }
                    if(filled($request->file('logo'))){
                    $value = $request->file($name)->store("images", "public");
                    }else {
                        $value = $request->file('profile')->store('/settings/images','public');
                    }
                }
                $setting->update(['value' => $value]);
            }
        }
        return response()->success('تنظیمات با موفقیت ثبت شد.');
    }
}

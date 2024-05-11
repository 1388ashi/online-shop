<?php

namespace Modules\Slider\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Slider\Http\Requests\Slider\storeRequest;
use Modules\Slider\Models\Slider;

class SliderController extends Controller
{
        
    public function index()
    {
        $sliders =  Slider::all();

        return response()->success('',compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(storeRequest $request): JsonResponse
    {
        
        $slider = Slider::query()->create([
            'name' => $request->input('name'),
            'link' => $request->input('link'),
            'status' => $request->input('status'),
        ]);
        $slider->uploadFiles($request);
        
        return response()->success('اسلایدر با موفقیت ثبت شد.');
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(storeRequest $request, Slider $slider): JsonResponse
    {
        $slider->update([
            'name' => $request->name,
            'parent_id' => $request->input('parent_id'),
            'featured' => $request->input('featured'),
            'status' => $request->input('status'),
        ]);
        $slider->uploadFiles($request);

        return response()->success('اسلایدر با موفقیت ویرایش شد.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider): JsonResponse
    {
        $slider->delete();

        return response()->success('اسلایدر با موفقیت حذف شد.');
    }
}

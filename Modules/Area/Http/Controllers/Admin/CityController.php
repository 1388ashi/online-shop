<?php

namespace Modules\Area\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Area\Entities\City;
use Modules\Area\Entities\Province;
use Modules\Area\Http\Requests\Admin\CityStoreRequest;
use Modules\Area\Http\Requests\Admin\CityUpdateRequest;

class CityController extends Controller
{
    public function index(): JsonResponse
    {
        // $filters = EloquentFilters::make([
        //     new StringFilter('name', request('name')),
        //     new IntegerFilter('province_id', request('province_id')),
        //     new BooleanFilter('status', request('status')),
        // ]);

        $cities = City::query()
            // ->sortable()
            // ->filter($filters)
            ->orderBy('name', 'asc')
            ->with('province:id,name')
            ->paginate();

        $provinces = Province::getAllProvinces();

        return response()->success('',compact('cities','provinces'));
    }

    public function store(CityStoreRequest $request): JsonResponse
    {
        $province = Province::find($request->input('province_id'));
        $province->cities()->create([
            'name' => $request->input('name'),
            'status' => $request->input('status')
        ]);

        //clear cache
        City::clearCitiesCacheByProvince($province->id);

        return response()->success('شهر با موفقیت حذف شد.');
    }
    
    public function update(CityUpdateRequest $request, City $city): JsonResponse
    {
        $city->update($request->safe()->except('province_id'));
        
        if ($city->province_id != $request->input('province_id')) {
            $province = Province::find($request->input('province_id'));
            $city->province()->associate($province);
            $city->save();
        }

        //clear cache
        City::clearCitiesCacheByProvince($city->province_id);
        
        return response()->success('شهر ویرایش شد.');
    }
    
    public function destroy(City $city): JsonResponse
    {
        $city->delete();
        
        //clear cache
        City::clearCitiesCacheByProvince($city->province_id);

        return response()->success('شهر با موفقیت حذف شد.');
    }
}

@extends('layouts.admin.master')

@section('content')
    <!--  Page-header opened -->
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fe fe-life-buoy ml-1"></i>
                    داشبورد</a></li>
            <li class="breadcrumb-item active" aria-current="page">لیست شهرها</li>
        </ol>
        <div class="mt-3 mt-lg-0">
            <div class="d-flex align-items-center flex-wrap text-nowrap">
                <a href="{{ route('admin.cities.create') }}" class="btn btn-indigo">
                    ثبت شهر جدید
                    <i class="fa fa-plus"></i>
                </a>
            </div>
        </div>
    </div>
    <!--  Page-header closed -->

    <!--advance search-->
    <form method="get" action="{{route('admin.cities.index')}}"
          autocomplete="off"
          onblur="document.form1.input.value = this.value;">
        <div class="row">
            <div class="col-xl-12 col-md-12 col-lg-12">
                {{--            <div class="card card-collapsed">--}}
                <div class="card">
                    <div class="card-header  border-0">
                        <div class="card-title" data-toggle="card-collapse" style="font-size: 16px;font-weight: bold;">
                            جستجوی پیشرفته
                        </div>
                        <div class="card-options">
                            <a href="#" class="card-options-collapse" data-toggle="card-collapse"
                               style="margin: 5px;"><i
                                    class="fe fe-chevron-up"></i></a>
                            <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"
                               style="margin: 5px;"><i
                                    class="fe fe-maximize"></i></a>
                            <a href="#" class="card-options-remove" data-toggle="card-remove" style="margin: 5px;"><i
                                    class="fe fe-x"></i></a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">نام شهر</label>
                                    <input class="form-control" id="name" name="name" type="text"
                                           value="{{ request('name') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="province_id">استان</label>
                                    <select class="form-control" name="province_id" id="province_id">
                                        <option>همه استان ها</option>
                                        @foreach($provinces as $id => $province)
                                            <option
                                                value="{{ $id }}"{{ request('province_id') == $id ? ' selected' : '' }}>{{ $province }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">وضعیت</label>
                                    <select class="form-control" name="status" id="status">
                                        <option>همه</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>فعال</option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>غیرفعال
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">جستجو</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- end advance Search-->

    <!-- row opened -->
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">لیست همه شهرها ({{ $cities->total() }})</div>
                    <div class="card-options">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a>
                        <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i
                                class="fe fe-maximize"></i></a>
                        <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example-2" class="table table-striped table-bordered text-nowrap text-center">
                            <thead>
                            <tr>
                                <th class="wd-20p border-bottom-0">@sortablelink('id', 'شناسه')</th>
                                <th class="wd-20p border-bottom-0">@sortablelink('name', 'نام')</th>
                                <th class="wd-20p border-bottom-0">@sortablelink('province_id', 'استان')</th>
                                <th class="wd-20p border-bottom-0">@sortablelink('status', 'وضعیت')</th>
                                <th class="wd-25p border-bottom-0">@sortablelink('created_at', 'تاریخ ثبت')</th>
                                <th class="wd-10p border-bottom-0">عملیات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($cities as $city)
                                <tr>
                                    <td>{{ $city->id }}</td>
                                    <td>{{ $city->name }}</td>
                                    <td>{{ $city->province->name }}</td>
                                    <td>
                                        @include('components.status', ['status' => $city->status])
                                    </td>
                                    <td>@jalaliDate($city->created_at)</td>
                                    <td>
                                        {{-- Edit--}}
                                        <a href="{{ route('admin.cities.edit', [$city->id]) }}"
                                           class="btn btn-warning btn-sm text-white" data-toggle="tooltip"
                                           data-original-title="ویرایش"><i class="fa fa-pencil"></i></a>
                                        {{-- Delete--}}
                                        <button class="btn btn-danger btn-sm text-white"
                                                onclick="confirmDelete('delete-{{ $city->id }}')" @disabled($city->isDeletable())>
                                            <i class="fa fa-trash-o"></i></button>
                                        <form action="{{ route('admin.cities.destroy', $city->id) }}" method="post"
                                              id="delete-{{ $city->id }}" style="display: none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <p class="text-danger"><strong>در حال حاضر هیچ شهری یافت نشد!</strong></p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        {{ $cities->links() }}
                    </div>
                </div>
                <!-- table-wrapper -->
            </div>
            <!-- section-wrapper -->
        </div>
    </div>
    <!-- row closed -->
@endsection

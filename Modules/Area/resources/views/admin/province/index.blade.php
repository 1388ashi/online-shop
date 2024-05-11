@extends('layouts.admin.master')

@section('content')
    <!--  Page-header opened -->
    <div class="page-header">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fe fe-life-buoy ml-1"></i> داشبورد</a></li>
            <li class="breadcrumb-item active" aria-current="page">لیست استان ها</li>
        </ol>
        <div class="mt-3 mt-lg-0">
            <div class="d-flex align-items-center flex-wrap text-nowrap">
{{--                <a href="{{ route('user.salaries.create') }}" class="btn btn-indigo">--}}
{{--                    ثبت استان جدید--}}
{{--                    <i class="fa fa-plus"></i>--}}
{{--                </a>--}}
            </div>
        </div>
    </div>
    <!--  Page-header closed -->

    <!-- row opened -->
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">لیست همه استان ها ({{ $provinces->total() }})</div>
                    <div class="card-options">
                        <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                        <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
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
                                <th class="wd-20p border-bottom-0">@sortablelink('status', 'وضعیت')</th>
                                <th class="wd-25p border-bottom-0">@sortablelink('created_at', 'تاریخ ثبت')</th>
{{--                                <th class="wd-10p border-bottom-0">عملیات</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($provinces as $province)
                                <tr>
                                    <td>{{ $province->id }}</td>
                                    <td>{{ $province->name }}</td>
                                    <td>
                                        @include('components.status', ['status' => $province->status])
                                    </td>
                                    <td>{{ $province->created_at }}</td>
{{--                                    <td>--}}
{{-- Edit--}}
{{--                                        <a href="{{ route('user.salaries.edit', [$province->id]) }}" class="btn btn-warning btn-sm text-white" data-toggle="tooltip" data-original-title="ویرایش"><i class="fa fa-pencil"></i></a>--}}
{{-- Delete--}}
{{--                                        <button class="btn btn-danger btn-sm text-white" onclick="confirmDelete('delete-{{ $province->id }}')"><i class="fa fa-trash-o"></i></button>--}}
{{--                                        <form action="{{ route('user.provinces.destroy', $province->id) }}" method="post" id="delete-{{ $province->id }}" style="display: none">--}}
{{--                                            @csrf--}}
{{--                                            @method('DELETE')--}}
{{--                                        </form>--}}
{{--                                    </td>--}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <p class="text-danger"><strong>در حال حاضر هیچ استانی یافت نشد!</strong></p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        {{ $provinces->appends(request()->query())->links() }}
                    </div>
                </div>
                <!-- table-wrapper -->
            </div>
            <!-- section-wrapper -->
        </div>
    </div>
    <!-- row closed -->
@endsection

@extends('smstoken::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('smstoken.name') !!}</p>
@endsection

@extends('admin.layout.auth')
@section("content")
@section('css')
<style>
    .custom-step a {
        margin-left: 2px;
    }
    .margin-label{
        padding-top:6px;
    }
</style>
@include('common/application/css')
<link rel="stylesheet" href="{{asset_public("css/tab_style.css")}}">
@endsection
<div class="container" id="page-content">
    {{-- <div class="btn-group btn-group-justified custom-step">
        <a href="#" class="btn btn-primary">Apple</a>
        <a href="#" class="btn btn-primary">Samsung</a>
        <a href="#" class="btn btn-primary">Sony</a>
    </div> --}}
{{-- <form id="application_form" action="{{route("student.application.store")}}" method="POST" class="form-horizontal" enctype="multipart/form-data"> --}}
    @include('common/application/form')
{{-- </form> --}}
</div>
@endsection
@section('js')
    @include("common/application/js")
@endsection
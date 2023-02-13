@extends('admin.layout.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                {{-- <div class="panel-heading">Payment <span class="pull-right"></div> --}}
                <div class="panel-body">
                    @include('common.application.payment-receipt')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
    @include('common/application/peyment-receipt-js')
@endsection
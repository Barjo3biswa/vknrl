@extends('student.layouts.auth')
@section("content")
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Application 
                    @if(!getActiveSessionApplication())
                    <span class="pull-right"><a href="{{route("student.application.create")}}"><button
                    class="btn btn-sm btn-primary"> Apply New Application</button></a></span>
                    @endif</div>
                <div class="panel-body">
                   @include("common.application.index")
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
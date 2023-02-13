@extends('admin.layout.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Exam Center's
                    <strong>: Create</strong>
                    <span class="pull-right"><a href="{{route("admin.exam-center.index")}}"><button
                                class="btn btn-sm btn-primary"> View All</button></a></span>
                </div>
                <div class="panel-body">
                    <form action="{{route("admin.exam-center.store")}}" id="exam-center-create" class="form-horizontal"
                        method="post">
                        @include("admin.exam_center.common")
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function(){
            $("form#exam-center-create").validate();
        });
</script>
@endsection
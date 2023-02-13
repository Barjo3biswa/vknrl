@extends('admin.layout.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Exam Center's
                    <strong>: Edit</strong>
                    <span class="pull-right"><a href="{{route("admin.exam-center.index")}}"><button
                                class="btn btn-sm btn-primary"> View All</button></a></span>
                </div>
                <div class="panel-body">
                    <form action="{{route("admin.exam-center.update", Crypt::encrypt($exam_center->id))}}" id="exam-center-create" class="form-horizontal"
                        method="post">
                        <input type="hidden" name="_method" value="PUT">
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
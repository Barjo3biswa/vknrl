@extends('admin.layout.auth')
@section("css")
<style>
    .row-effect{
        border: 2px dashed red;
    }
</style>
@endsection
@section("content")
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Applications Upload Qualified Student List
                    <span class="pull-right"><a href="{{request()->fullUrlWithQuery(['export-data' => 1])}}"><button class="btn btn-sm btn-primary"> Download Sample</button></a></span>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{route("admin.application.upload.student.qualified.post")}}" enctype="multipart/form-data">
                        {{ csrf_field() }}                        
                        <div class="form-group">
                            <label for="file">Upload Excel File <span class="text-danger">(Qualified Student List)</span></label>
                            <input type="file" class="form-control" name="file" required accept=".xls,.xlsx">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-md btn-success"><i class="glyphicon glyphicon-cloud-upload"></i> Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
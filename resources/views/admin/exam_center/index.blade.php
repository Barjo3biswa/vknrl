@extends('admin.layout.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Exam Center's
                    <strong>: {{$exam_centers->total()}} records found.</strong>
                    <span class="pull-right"><a href="{{route("admin.exam-center.create")}}"><button
                                class="btn btn-sm btn-primary"> Create Exam Center</button></a></span>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Center Code</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Pin</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            @forelse ($exam_centers as $key =>  $exam_center)
                            <tr id="row_{{$exam_center->id}}" data-center-id="{{Crypt::encrypt($exam_center->id)}}" data-exam-center="{{collect($exam_center->only(["id", "center_code", "center_name", "address", "city", "state", "pin"]))->toJson()}}">
                                <td class="ids">{{ $key+ 1 + ($exam_centers->perPage() * ($exam_centers->currentPage() - 1)) }}</td>
                                <td class="center_code">{{$exam_center->center_code ?? "----"}}</td>
                                <td class="center_name">{{$exam_center->center_name}}</td>
                                <td class="address">{{$exam_center->address}}</td>
                                <td class="city">{{$exam_center->city}}</td>
                                <td class="state">{{$exam_center->state}}</td>
                                <td class="pin">{{$exam_center->pin}}</td>
                                <td><a target="_blank" href="{{route("admin.exam-center.edit", Crypt::encrypt($exam_center->id))}}"><button type="button" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</button></a>
                                    <a href="{{route("admin.exam-center.destroy", Crypt::encrypt($exam_center->id))}}" onclick="if(confirm('Are you sure ?')){ event.preventDefault();
                                                    document.getElementById('delete-form-{{$exam_center->id}}').submit();}else{return false}">
                                        <button type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                    </a>

                                <form id="delete-form-{{$exam_center->id}}" action="{{route("admin.exam-center.destroy", Crypt::encrypt($exam_center->id))}}" method="POST"
                                        style="display: none;">
                                        {{ csrf_field() }}
                                        {{ method_field("DELETE") }}
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-danger">No Records Found</td>
                            </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
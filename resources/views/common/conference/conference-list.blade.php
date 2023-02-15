@extends('admin.layout.auth')
@section("content")
<div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Filter: </div>
                    <div class="panel-body">
                        <form action="" method="get">
                            <div class="filter dont-print">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <label for="registration_no" class="label-control">Registration No:</label>
                                        <input type="text" name="registration_no" id="registration_no" class="form-control"
                                            placeholder="Registration No" value="{{request()->get("registration_no")}}">
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="email" class="label-control">Applicant Email:</label>
                                        <input type="text" name="email" id="email" class="form-control"
                                            placeholder="Applicant Email" value="{{request()->get("email")}}">
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="email" class="label-control">Applicant Mobile:</label>
                                        <input type="text" name="mobile_no" id="mobile_no" class="form-control"
                                            placeholder="Applicant Mobile" value="{{request()->get("mobile_no")}}">
                                    </div>
                                    
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="submit" class="label-control" style="visibility: hidden;">Search</label><br>
                                        <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
                                        <a href="{{route("admin.conference.complete")}}" class="btn btn-danger"><i class="fa fa-remove"></i> Reset</a>
                                        <a class="btn btn-primary" href="{{request()->fullUrlWithQuery(['export_data' => 1])}}">Export</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
            <div class="panel-heading">Registered Applicant's: <strong>{{$list->total()}} records found</strong></div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Registration No</th>
                                    <th>Mobile No</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($list as $key => $applicant)
                                <tr>
                                    <td>{{ $key+ 1 + ($list->perPage() * ($list->currentPage() - 1)) }}</td>
                                    <td>{{$applicant->first_name.' '.$applicant->middle_name.' '.$applicant->last_name}}</td>
                                    <td>{{$applicant->registration_no}}</td>
                                    <td>{{$applicant->phone_no}}</td>
                                    <td>{{$applicant->email}}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-danger text-center">No Records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{$list->appends(request()->all())->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
</script>    
@endsection
@extends('admin.layout.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                {{-- <div class="panel-heading">Welcome to VKNRL Admission Portal </div> --}}

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Registered Applicants</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div class="widget-body clearfix">
                                    <div class="pull-left">
                                        <i class="fa fa-users"></i>
                                    </div>
                                <div class="pull-right number"><a href="{{route("admin.applicants.list")}}">{{totalRegisterdUser()}}</a></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Total Applications</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div class="widget-body clearfix">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                <div class="pull-right number"><a href="{{route("admin.application.index")}}">{{getTotalApplicationCount()}}</a></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Registered Applicants Conference </div>
                                    <div class="pull-right"></div>
                                </div>
                                <div class="widget-body clearfix">
                                    <div class="pull-left">
                                        <i class="fa fa-users"></i>
                                    </div>
                                <div class="pull-right number"><a href="{{route("admin.conference.list")}}">{{totalRegisterdUserConf()}}</a></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-6">
                            <div class="widget">
                                <div class="widget-heading clearfix">
                                    <div class="pull-left">Total Applications<br/> Conference</div>
                                    <div class="pull-right"></div>
                                </div>
                                <div class="widget-body clearfix">
                                    <div class="pull-left">
                                        <i class="fa fa-list"></i>
                                    </div>
                                <div class="pull-right number"><a href="#">{{getTotalApplicationCountConf()}}</a></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
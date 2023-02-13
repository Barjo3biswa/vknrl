@extends('admin.layout.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Filter: </div>
                <div class="panel-body">
                    <form action="" method="get">
                        @include('admin/logs/filter')
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
                <div class="panel-heading">Admit Card's
                    <strong>: {{$logs->total()}} records found.</strong>
                    {{--  <span class="pull-right"><a href="{{route("admin.admit-card.create")}}"><button
                        class="btn btn-sm btn-primary"> Generate Admit Card</button></a>
                    <a href="{{request()->fullUrlWithQuery(['export-excel' => 1])}}"><button
                            class="btn btn-sm btn-info"> Export Seat Plan</button></a>
                    </span> --}}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User Type</th>
                                    <th>Activity</th>
                                    <th>Username</th>
                                    <th>IP</th>
                                    <th>Agent</th>
                                    <th>Date Time</th>
                                </tr>
                            </thead>
                            @forelse ($logs as $key => $log)
                            <tr>
                                <td class="ids">
                                    {{ ( $key+ 1 + ($logs->perPage() * ($logs->currentPage() - 1))) }}
                                </td>
                                <td>{{$log->guard}}</td>
                                <td>{{$log->activity}}</td>
                                <td>{{$log->username}}</td>
                                <td>{{$log->ip}}</td>
                                <td>{{detectBrowser($log->agent)}}</td>
                                <td>{{dateFormat($log->created_at, "d-m-Y h:i a")}}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center text-danger">No Records Found</td>
                            </tr>
                            @endforelse
                        </table>
                        {{$logs->appends(request()->all())->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    $(document).ready(function(){
        $("#myTable").find("td").prop({
            "tabindex" : 1
        });
        $(document).on("focus", "#myTable td", function(){
            $(this).parents("tr").addClass("row-effect");
        });
        $(document).on("focusout", "#myTable td", function(){
            $(this).parents("tr").removeClass("row-effect");
        });
        $("button[type='reset']").on("click", function(){
            $(".filter input").attr("value", "").val("");
            $(".filter").find("select").each(function(index, element){
                $(element).find("option").each(function(){
                    if (this.defaultSelected) {
                        this.defaultSelected = false;
                        // this.selected = false;
                        $(element).val("").val("all");
                        return;
                    }
                });
            });
        });
        $('input#date_from').Zebra_DatePicker({ 
            format : 'd-m-Y',
            readonly_element:false,
            // direction: [-1,false],
            // direction: false,
            pair: $('input#date_to'),
            onSelect: function() { 
                $(this).change();
                // console.log($(this).val());
            }
        });
        $('input#date_to').Zebra_DatePicker({ 
            format : 'd-m-Y',
            readonly_element:false, 
            direction: 1,
            onSelect: function() { 
                $(this).change();
                // console.log($(this).val());
            }
        });
        $('input.zebra').each(function(index, element){
            $(this).attr({
                "data-inputmask" : "'alias': 'dd-mm-yyyy'",
                "pattern": "(?:(?:0[1-9]|1[0-9]|2[0-9])-(?:0[1-9]|1[0-2])|(?:30)-(?:(?!02)(?:0[1-9]|1[0-2]))|(?:31)-(?:0[13578]|1[02]))-(?:19|20)[0-9]{2}"
            });
            $(this).inputmask();
        });
        //input mask bundle ip address
        var ipv4_address = $('#ip');
        ipv4_address.inputmask({
            alias: "ip",
            greedy: false //The initial mask shown will be "" instead of "-____".
        });
    });
</script>
@endsection
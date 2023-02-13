@extends('admin.layout.auth')
@section('css')
<link rel="stylesheet" href="{{asset_public("css/datatables.min.css")}}">
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Filter: </div>
                <div class="panel-body">
                    <form action="" method="get">
                        @include('admin/admin_card/filter')
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
                    <strong>: {{$applications->count()}} records found.</strong>
                    <span class="pull-right"><a href="{{route("admin.admit-card.index")}}"><button
                                class="btn btn-sm btn-primary"> View all generated Admit Card</button></a></span>
                </div>
                <div class="panel-body">
                    <form action="{{route("admin.admit-card.store")}}" class="form-horizontal" method="POST"
                        onsubmit="return checkFormData(this)">
                        {{ csrf_field() }}
                        <p class="text-warning">Please select below information to generate new Admit Card.</p>
                        <div class="row">
                            <div class="col-md-3">
                                <label for="exam-center">Exam Center <span class="text-danger">*</span></label>
                                <select name="exam_center" id="exam-center" required class="form-control">
                                    <option value="" disabled selected>--SELECT--</option>
                                    @forelse ($exam_centers as $exam_center)
                                    <option value="{{$exam_center->id}}">{{$exam_center->center_name}}</option>
                                    @empty

                                    @endforelse
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="exam-center">Exam Date <span class="text-danger">*</span></label>
                                <input type="text" name="date" id="date" class="form-control zebra" required>
                            </div>
                            <div class="col-md-3">
                                <label for="exam-center">Exam Time <span class="text-danger">* (eg. 09:30
                                        am)</span></label>
                                <input type="text" name="time" id="time" class="form-control time" required>
                            </div>
                            <div class="col-md-3">
                                <label for="exam-center">Publish Admit<span class="text-danger">*</span></label>
                                <select name="publish" id="public" class="form-control" required>
                                    <option value="" selected disabled>--SELECT--</option>
                                    <option value="0">Draft</option>
                                    <option value="1">Publish</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="admin_card_tables">
                                <caption><span class="text-danger">Info:</span> <span class="text-info">Click table
                                        header to sort accordingly.</span></caption>
                                <thead>
                                    <tr>
                                        <th><label class="checkbox-inline"><input type="checkbox" id="select-all"
                                                    value="">Select All</label></th>
                                        <th>#</th>
                                        <th>Application ID</th>
                                        <th>Registration ID</th>
                                        <th>Name</th>
                                        <th>Caste/Category</th>
                                        <th>Vill./Town (Correspondence)</th>
                                        <th>District (Correspondence)</th>
                                    </tr>
                                </thead>
                                @forelse ($applications as $key => $application)
                                <tr id="row_{{$application->id}}" data-center-id="{{Crypt::encrypt($application->id)}}"
                                    data-exam-center="{{collect($application->only(["id"]))->toJson()}}">
                                    <td><label class="checkbox-inline"><input type="checkbox" class="check"
                                                name="applications[]" value="{{$application->id}}">&nbsp;</label></td>
                                    <td class="ids">{{ $key + 1 + ($applications->perPage() * ($applications->currentPage() - 1))}}</td>
                                    <td class="center_code">{{$application->id ?? "----"}}</td>
                                    <td class="center_name">{{$application->student_id}}</td>
                                    <td class="address">{{$application->fullname}}</td>
                                    <td class="city">{{$application->caste->name}}</td>
                                    <td class="state">{{$application->correspondence_village_town}}</td>
                                    <td class="pin">{{$application->correspondence_district}}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-danger">No Records Found</td>
                                </tr>
                                @endforelse
                            </table>
                            {{$applications->appends(request()->all())->links()}}
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success btn-md"><i
                                        class="glyphicon glyphicon-list-alt"></i> Generate</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section("js")
<script src="{{asset_public("js/datatables.min.js")}}"></script>
<script>
    $(document).ready(function(){
        var dataTable = $("#admin_card_tables").DataTable({
            "paging":   false,
            aaSorting: [[2, 'asc']],
            "aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": [ 0, 1 ] 
                }
            ]
        });
        dataTable.on( 'order.dt search.dt', function () {
            dataTable.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1 + {{($applications->perPage() * ($applications->currentPage() - 1))}};
            } );
        } ).draw();
        $('input#date').Zebra_DatePicker({
            format : 'd-m-Y',
            readonly_element:false,
            direction: true,
            onSelect: function() { 
                $(this).change();
                // console.log($(this).val());
            }
        });
        $('#time')
        .inputmask({
            alias: 'hh:mm t',      
            showMaskOnHover: true,
            showMaskOnFocus: true,
            oncomplete: function(){ console.log('complete'); },
        })
        .on('cut', function(evt) {
            console.log(evt);
        })
        .on('paste', function(evt) {
            console.log(evt);
        });
        $('input.zebra').each(function(index, element){
            $(this).attr({
                "data-inputmask" : "'alias': 'dd-mm-yyyy'",
                "pattern": "(?:(?:0[1-9]|1[0-9]|2[0-9])-(?:0[1-9]|1[0-2])|(?:30)-(?:(?!02)(?:0[1-9]|1[0-2]))|(?:31)-(?:0[13578]|1[02]))-(?:19|20)[0-9]{2}"
            });
            $(this).inputmask();
        });
        $("#select-all").change(function(){
            if($(this).is(":checked")){
                $(".check").prop("checked", true);
            }else{
                $(".check").prop("checked", false);
            }
        });
        $(".check").change(function(){
            if($(".check").length == $(".check:checked").length){
                $("#select-all").prop("checked", true);
            }else{
                $("#select-all").prop("checked", false);
            }
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
    });
    checkFormData = function(Obj){
        return true;
        if(confirm("Are you sure ?")){
            if($(".check:checked").length == 0){
                alert("Please select at-least one application to generate Admin Card");
                return false;
            }
            return true;
        }
        return false;
    }
</script>
@endsection
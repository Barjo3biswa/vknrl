@extends('admin.layout.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Filter: </div>
                <div class="panel-body">
                    <form action="" method="get">
                        @include('admin/admin_card/index-filter')
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
                    <strong>: {{$admit_cards->total()}} records found.</strong>
                    <span class="pull-right"><a href="{{route("admin.admit-card.create")}}"><button
                                class="btn btn-sm btn-primary"> Generate Admit Card</button></a>
                                <a href="{{request()->fullUrlWithQuery(['export-excel' => 1])}}"><button class="btn btn-sm btn-info"> Export Seat Plan</button></a>
                            </span>
                </div>
                <div class="panel-body">
                    <form action="{{route("admin.admit-card.publish-all")}}" method="POST">
                        {{ csrf_field() }}
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th><label class="checkbox-inline"><input type="checkbox" id="select-all"
                                                    value="">Select All</label></th>
                                        <th>#</th>
                                        <th>Application ID</th>
                                        <th>Registration ID</th>
                                        <th>Name</th>
                                        <th>Exam Center</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                @forelse ($admit_cards as $key => $admit_card)
                                <tr id="row_{{$admit_card->id}}" data-center-id="{{Crypt::encrypt($admit_card->id)}}"
                                    data-exam-center="{{collect($admit_card->only(["id", "center_code", "center_name", "address", "city", "state", "pin"]))->toJson()}}">
                                    <td>
                                        @if (!$admit_card->publish)
                                        <label class="checkbox-inline"><input type="checkbox" class="check"
                                                name="admit_cards[]" value="{{$admit_card->id}}">&nbsp;</label>
                                        @endif
                                    </td>
                                    <td class="ids">{{ ( $key+ 1 + ($admit_cards->perPage() * ($admit_cards->currentPage() - 1))) }}</td>
                                    <td class="center_code">{{$admit_card->application_id ?? "----"}}</td>
                                    <td class="center_name">{{$admit_card->application->student_id}}</td>
                                    <td class="address">{{$admit_card->application->fullname}}</td>
                                    <td class="city">{{$admit_card->exam_center->center_name}}</td>
                                    <td class="state">{{dateFormat($admit_card->exam_date)}}</td>
                                    <td class="pin">{{$admit_card->exam_time}}</td>
                                    <td class="status">{{$admit_card->publish ? "Published" : "Draft"}}</td>
                                    <td class="action_buttons">
                                        <a href="{{route("admin.admit-card.show", Crypt::encrypt($admit_card->id))}}"
                                            target="_blank"> <button class="btn btn-sm btn-primary" type="button"><i
                                                    class="glyphicon glyphicon-eye-open"></i> View</button></a>
                                        @if (!$admit_card->publish)
                                        <button class="btn btn-sm btn-warning" type="button"
                                            onClick="return publishSingleAdmitCard(this)"><i
                                                class="glyphicon glyphicon-ok-sign"></i> Publish</button>
                                        @else
                                        {{-- <button class="btn btn-sm btn-default" type="button"><i class="glyphicon glyphicon-download"></i> Download</button> --}}
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-danger">No Records Found</td>
                                </tr>
                                @endforelse
                            </table>
                            {{$admit_cards->appends(request()->all())->links()}}
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success" type="submit">Publish Admit Card</button>
                            </div>
                        </div>
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
        });
        publishSingleAdmitCard = function (Obj){
            console.log(Obj);
            if(!confirm("Publish Single Admit Card ?")){
                return false;
            }
            $this = $(Obj);
            var $current_row = $this.parents("tr").find(".check");
            $current_row.prop("checked", true);
            $this.parents("form").find(".check").not($current_row).prop("checked", false).trigger("change");
            $this.parents("form").submit();
        }
</script>
@endsection
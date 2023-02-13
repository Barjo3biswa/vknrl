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
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Filter: </div>
                <div class="panel-body">
                    <form action="" method="get">
                        @include('admin/applications/filter')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Applications 
                    <strong>: {{$applications->total()}} records found.</strong>
                    <span class="pull-right"><a href="{{request()->fullUrlWithQuery(['export-data' => 1])}}"><button class="btn btn-sm btn-primary"> Export to Excel</button></a></span>
                </div>
                <div class="panel-body">
                    <form method="POST" action="{{route("admin.application.sms.send")}}" onsubmit="return applicationSelected(this)">
                        {{ csrf_field() }}
                        @include("common.application.index")
                        <br>
                        <br>
                        <br>

                        @php
                        $sms_templates = config("vknrl.sms_templates");
                            
                        @endphp

                        <div class="form-group row">
                            <div class="col-md-6">
                                {{-- <label for="sms" class="control-label">SMS <span class="text-danger">Please type your message below</span></label>
                                <textarea required name="sms" id="sms" cols="30" rows="3" class="form-control" placeholder="Type your message here"></textarea>
                                <span class="text-right pull-right" id="sms_counter">0</span> --}}
                                <label for="sms" class="control-label">Please choose a sms template to send</label>
                                <select class="form-control" name="template_id" id="template_id" required>
                                    <option value="">--Choose--</option>
                                    @foreach ($sms_templates as $template)
                                        <option value="{{$template["template_id"]}}" data-template="{{$template["template"]}}">{{$template["name"]}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="sms" class="control-label">SMS <span
                                            class="text-danger">Please type your message
                                            below</span></label>
                                    <textarea name="sms" id="sms" cols="30" rows="3"
                                        class="form-control"
                                        placeholder="Type your message here"></textarea>
                                    <span class="text-right pull-right" id="sms_counter">0</span>
                                </div>
                                <div class="col-md-6">
                                    <h5># <span id="counter">0</span> Application Selected</h5>
                                    <h5># Place Dynamic Name, Application No, Registration No, In SMS</h5>
                                    <p>
                                        <strong>##name##</strong> : <strong>Applicant Name</strong><br>
                                        <strong>##app_no##</strong> : <strong>Application No</strong><br>
                                        <strong>##reg_no##</strong> : <strong>Registration No</strong><br>
                                        <strong>##hold_reason##</strong> : <strong>Hold Reason</strong><br>
                                        <strong>##rejected_reason##</strong> : <strong>Rejected Reason</strong><br>
                                        <strong>##school_name##</strong> : <strong>{{env("APP_NAME")}}</strong><br>
                                    </p>
                                </div>
                            </div>
                            
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-md"><i class="glyphicon glyphicon-send"></i> Send SMS</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <a class="btn btn-primary" data-toggle="modal" href='#verification-modal'>Verify Modal</a> --}}
<div class="modal fade" id="verification-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-success">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Confim Verification</h4>
            </div>
            <form action="" method="POST" id="form-verification">
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Application No</th>
                                    <th id="application_no"></th>
                                </tr>
                                <tr>
                                    <th>Full Name</th>
                                    <th id="fullname"></th>
                                </tr>
                                <tr>
                                    <th>Caste</th>
                                    <th id="caste"></th>
                                </tr>
                                <tr>
                                    <th>ANM/LHV</th>
                                    <th id="anm_or_lhv"></th>
                                </tr>
                                <tr>
                                    <th>English mark obtained in 12th</th>
                                    <th id="english_mark_obtain"></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Are you sure ?')">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="rejecttion-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-danger">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Confim Rejection</h4>
            </div>
            <form action="" method="POST" id="form-rejection">
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Application No</th>
                                    <th id="application_no"></th>
                                </tr>
                                <tr>
                                    <th>Full Name</th>
                                    <th id="fullname"></th>
                                </tr>
                                <tr>
                                    <th>Caste</th>
                                    <th id="caste"></th>
                                </tr>
                                <tr>
                                    <th>ANM/LHV</th>
                                    <th id="anm_or_lhv"></th>
                                </tr>
                                <tr>
                                    <th>English mark obtained in 12th</th>
                                    <th id="english_mark_obtain"></th>
                                </tr>
                                <tr>
                                    <th>Rejection Reason:</th>
                                    <th>
                                        <textarea required name="rejection_reason" id="rejection_reason" cols="30" rows="3" placeholder="Rejection Reason" class="form-control" minlength="10"></textarea>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure ?')">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="on-hold-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Confim Onhold</h4>
            </div>
            <form action="" method="POST" id="form-onhold">
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Application No</th>
                                    <th id="application_no"></th>
                                </tr>
                                <tr>
                                    <th>Full Name</th>
                                    <th id="fullname"></th>
                                </tr>
                                <tr>
                                    <th>Caste</th>
                                    <th id="caste"></th>
                                </tr>
                                <tr>
                                    <th>ANM/LHV</th>
                                    <th id="anm_or_lhv"></th>
                                </tr>
                                <tr>
                                    <th>English mark obtained in 12th</th>
                                    <th id="english_mark_obtain"></th>
                                </tr>
                                <tr>
                                    <th>Holding Reason:</th>
                                    <th id="">
                                        <textarea required name="holding_reason" id="holding_reason" cols="30" rows="3" placeholder="On Hold Reason" class="form-control" minlength="10"></textarea>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure ?')">Hold</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section("js")
<script>
    $("#template_id").change(function(event){
        var template = "";
        if($(this).val() !== ""){
            template = $("#template_id option:selected").data("template");
        }
        $("#sms").val(template);
    });
    var verification_url = '{{url("admin/application/accept/")}}';
    var reject_url = '{{url("admin/application/reject/")}}';
    var hold_url = '{{url("admin/application/hold/")}}';
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
        $('#verification-modal').on('hidden.bs.modal', function (e) {
            console.log("modal is hiding.");
            resetModalData("#verification-modal");
            $('#verification-modal form').unblock();
        })
        $('#rejection-modal').on('hidden.bs.modal', function (e) {
            console.log("modal is hiding.");
            resetModalData("#rejection-modal");
            $('#rejection-modal form').unblock();
        })
        $('#on-hold-modal').on('hidden.bs.modal', function (e) {
            console.log("modal is hiding.");
            resetModalData("#on-hold-modal");
            $('#on-hold-modal form').unblock();
        });
        $('#form-rejection').submit(function(event){
            event.preventDefault();
            var form_data = $(this).serialize();
            console.log(form_data);
            var url = reject_url+"/"+$(this).data("application-id");
            console.log(url);
            callAjax(url, form_data, this);

        });
        $('#form-verification').submit(function(event){
            event.preventDefault();
            var form_data = $(this).serialize();
            console.log(form_data);
            var url = verification_url+"/"+$(this).data("application-id");
            console.log(url);
            callAjax(url, form_data, this);
        });
        $('#form-onhold').submit(function(event){
            event.preventDefault();
            var form_data = $(this).serialize();
            console.log(form_data);
            var url = hold_url+"/"+$(this).data("application-id");
            console.log(url);
            callAjax(url, form_data, this);
        });
        $('input#payment_date_from').Zebra_DatePicker({ 
            format : 'd-m-Y',
            readonly_element:false,
            // direction: [-1,false],
            // direction: false,
            pair: $('input#payment_date_to'),
            onSelect: function() { 
                $(this).change();
                // console.log($(this).val());
            }
        });
        $('input#payment_date_to').Zebra_DatePicker({ 
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
        $("#select-all").change(function(){
            if($(this).is(":checked")){
                $(".check").prop("checked", true);
            }else{
                $(".check").prop("checked", false);
            }
            calculateSelectedApplication();
        });
        $(".check").change(function(){
            if($(".check").length == $(".check:checked").length){
                $("#select-all").prop("checked", true);
            }else{
                $("#select-all").prop("checked", false);
            }
            calculateSelectedApplication();
        });
        $("#sms").keyup(function(){
            var eachLine = $(this).val().split('\\n');
            $("#sms_counter").html($(this).val().length+eachLine.length);
        });
    });
    applicationSelected = function(){
        if(!$(".check:checked").length){
            alert("Please select at-least one application to send sms.");
            return false;
        }
        return true;
    }
    calculateSelectedApplication = function(){
        var counter = $(".check:checked").length;
        $("#counter").html(counter);
    }
    verfiyApplication  = function(Obj){
        setModalData('#verification-modal', Obj);
    }
    rejectApplication  = function(Obj){
        setModalData('#rejecttion-modal', Obj);
    }
    holdApplication    = function(Obj){
        setModalData('#on-hold-modal', Obj);
    }
    setModalData = function($modal_id, Obj){        
        var $data_list = $(Obj).parents("td").find("data").data("application");
        $($modal_id+" #application_no").html($data_list.application_no);
        $($modal_id+" #fullname").html($data_list.fullname);
        $($modal_id+" #caste").html($data_list.caste);
        $($modal_id+" #english_mark_obtain").html($data_list.english_mark_obtain);
        $($modal_id+" #anm_or_lhv").html($data_list.anm_or_lhv ? "Yes" : "No");
        $($modal_id).find("form").data("application-id", $(Obj).data("application-id"));
        $($modal_id).find("textarea").val("");
        $($modal_id).find("textarea").html("");
        $($modal_id).modal();
        console.log($($modal_id).find("form"));
    }
    resetModalData = function($modal_id){
        $($modal_id+" #application_no").html("");
        $($modal_id+" #fullname").html("");
        $($modal_id+" #caste").html("");
        $($modal_id+" #english_mark_obtain").html("");
        $($modal_id+" #anm_or_lhv").html("");
        $($modal_id).find("textarea").val("");
        $($modal_id).find("textarea").html("");
    }
    convertTableRow = function(application){
        console.log(application);
        var tr_row = $("#row_"+application.id+"");
        tr_row.find("#admin_buttons").html("");
        var status_str = application.status.replace("_", " ");
        var reason = (application.reject_reason ? application.reject_reason : application.hold_reason)
        status_str.charAt(0).toUpperCase()+status_str.slice(1);
        if(application.status != "accepted"){
            status_str ='<span data-toggle="tooltip" data-title="'+reason+'">'+status_str;
            status_str+='</span>';
        }
        tr_row.find("#status").html(status_str);
        $('[data-toggle="tooltip"]').tooltip();
    }
    callAjax = function(url, data, form_object){
        var block_ui_information = { 
                message: '<h4><img src="{{asset_public("images/busy.gif")}}"> Please Wait...</h4>'
            };
        // $(".loading").fadeIn();
        $(form_object).block(block_ui_information);
        // return false;
        var xhr = $.ajax({
            method:"POST",
            url:url,
            data:data+"&_method=PUT&_token={{csrf_token()}}",
            accepts :"application/json",
            "headers":{
                "_method":"PUT",
                "X-CSRF-TOKEN":window.laravel
            }
        });
        xhr.fail(function(resonse){
            // $(".loading").fadeOut();
            $(form_object).unblock();
            alert("Whoops! something went wrong please refresh the page and try again later.");
        });
        xhr.done(function(resonse){
            $(form_object).unblock();
            alert(resonse.message);
            if(resonse.status){
                $(form_object).parents(".modal").modal("hide");
            }
            convertTableRow(resonse.application);
            // $(".loading").fadeOut();

        });
    }
</script>
@endsection
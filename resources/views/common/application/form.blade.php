<?php
$guard = get_guard();
$step_wise_form_information = [
    "Personal Information" => [
        "class" => "", //disabled or btn colour
        "attribute" => "", //diasbled
    ],
    "Address" => [
        "class" => "", //disabled or btn colour
        "attribute" => "btn-info disabled", //diasbled
    ],
    "Academic Details" => [
        "class" => "", //disabled or btn colour
        "attribute" => "disabled", //diasbled
    ],
    "Certificate Upload" => [
        "class" => "", //disabled or btn colour
        "attribute" => "btn-info disabled", //diasbled
    ],
];
if(isset($application)){

    if($application->form_step == 1){
        $step_wise_form_information["Personal Information"]["class"] = "btn-info";
        $step_wise_form_information["Personal Information"]["attribute"] = "";
        
        $step_wise_form_information["Address"]["class"] = "btn-primary active";
        $step_wise_form_information["Address"]["attribute"] = "";
    }elseif($application->form_step == 2){
        $step_wise_form_information["Personal Information"]["class"] = "btn-info";
        $step_wise_form_information["Personal Information"]["attribute"] = "";
        
        $step_wise_form_information["Address"]["class"] = "btn-info";
        $step_wise_form_information["Address"]["attribute"] = "";
        
        
        $step_wise_form_information["Academic Details"]["class"] = "btn-primary active";
        $step_wise_form_information["Academic Details"]["attribute"] = "";
        
    }elseif($application->form_step == 3){
        $step_wise_form_information["Personal Information"]["class"] = "btn-info";
        $step_wise_form_information["Personal Information"]["attribute"] = "";
        
        $step_wise_form_information["Address"]["class"] = "btn-info";
        $step_wise_form_information["Address"]["attribute"] = "";

        $step_wise_form_information["Academic Details"]["class"] = "btn-info";
        $step_wise_form_information["Academic Details"]["attribute"] = "";
        
        
        $step_wise_form_information["Certificate Upload"]["class"] = "btn-primary active";
        $step_wise_form_information["Certificate Upload"]["attribute"] = "";
    }elseif($application->form_step == 4){
        $step_wise_form_information["Personal Information"]["class"] = "btn-primary active";
        $step_wise_form_information["Personal Information"]["attribute"] = "";

        $step_wise_form_information["Address"]["class"] = "btn-info";
        $step_wise_form_information["Address"]["attribute"] = "";
        
        $step_wise_form_information["Academic Details"]["class"] = "btn-info";
        $step_wise_form_information["Academic Details"]["attribute"] = "";
        
        $step_wise_form_information["Certificate Upload"]["class"] = "btn-info";
        $step_wise_form_information["Certificate Upload"]["attribute"] = "btn-info";
    }
}else {
    $step_wise_form_information["Personal Information"]["class"] = "btn-primary active";
    $step_wise_form_information["Personal Information"]["attribute"] = "";
}
?>
<div class="btn-group btn-group-justified" id="btn-steps">
    <a href="#step-one-update" class="btn btn-info {{$step_wise_form_information["Personal Information"]["class"]}}"
        {{$step_wise_form_information["Personal Information"]["attribute"]}} data-form="step-one-update"><i class="fa fa-users"></i> Personal Information</a>
    <a href="#step-two-update" class="btn btn-info {{$step_wise_form_information["Address"]["class"]}}"
        {{$step_wise_form_information["Address"]["attribute"]}}  data-form="step-two-update"><i class="fa fa-map-marker"></i> Address</a>
    <a href="#step-three-update" class="btn btn-info {{$step_wise_form_information["Academic Details"]["class"]}}"
        {{$step_wise_form_information["Academic Details"]["attribute"]}} data-form="step-three-update"><i class="fa fa-graduation-cap"></i> Academic Details</a>
    <a href="#step-four-update" class="btn btn-info {{$step_wise_form_information["Certificate Upload"]["class"]}}"
        {{$step_wise_form_information["Certificate Upload"]["attribute"]}}  data-form="step-four-update"><i class="fa fa-paperclip"></i> Certificate Upload</a>
</div>
<br><br>
@if(isset($application))
<form style="display:none" action="{{route($guard.".application.step_one_form", Crypt::encrypt($application->id))}}" name="step-one-update" method="POST" class="form-horizontal form-class application-form {{($application->form_step > 3 ? "active" : "")}}" enctype="multipart/form-data">
    {{ csrf_field() }} {{ method_field('PUT') }}
    @include('common.application.personal_information')
</form>
@if($application->form_step > 0)

    <form style="display:none" action="{{route($guard.".application.step_two_form", Crypt::encrypt($application->id))}}" name="step-two-update" method="POST" class="form-horizontal form-class application-form {{($application->form_step == 1 ? "active" : "")}}"
        enctype="multipart/form-data">
        {{ csrf_field() }} {{ method_field('PUT') }}
        @include('common.application.address_information')
    </form>
    @if($application->form_step > 1)
        <form style="display:none" action="{{route($guard.".application.step_three_form", Crypt::encrypt($application->id))}}" name="step-three-update" method="POST" class="form-horizontal form-class application-form {{($application->form_step == 2 ? "active" : "")}}"
            enctype="multipart/form-data">
            {{ csrf_field() }} {{ method_field('PUT') }}
            @include('common.application.academic_information')
        </form>
        @if($application->form_step > 2)
            <form style="display:none" action="{{route($guard.".application.step_final_form", Crypt::encrypt($application->id))}}" name="step-four-update" method="POST" class="form-horizontal form-class application-form {{($application->form_step == 3 ? "active" : "")}}"
                enctype="multipart/form-data">
                {{ csrf_field() }} {{ method_field('PUT') }}
                @include('common.application.attachment_information')
            </form>
        @endif
    @endif
@endif
@else
<form id="application_form" action="{{route("student.application.store")}}" method="POST"
    class="form-horizontal form-class application-form" enctype="multipart/form-data">
    {{ csrf_field() }}
    @include('common.application.personal_information')
</form>

@endif
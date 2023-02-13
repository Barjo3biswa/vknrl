<style>
        .bold {
            font-weight: bold;
        }
        table td, table th{
            padding: 7px;
            /* border-color: #ddd; */
        }
        table{
            /* border-color: #ddd; */
        }
        *{
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 11px;
        }
    </style>
    {{-- <link href="{{ asset_public('css/app.css') }}" rel="stylesheet"> --}}
    
    <div class="container" id="page-content">
        <div class="row">
            <table width="100%">
                <tbody>
                    <tr class="text-center">
                        <td width="20%" class="padding-xs">
                            <img style="max-width: 70px" width="70" class="avatar avatar-xxl"
                                src="{{base_path('public/logo.jpg')}}">
                        </td>
                        <td class="padding-xs" style="text-align: center;">
                            <div class="card-body text-center">
                                <h3 class="mb-3">VKNRL SCHOOL OF NURSING</h3>
                                <p class="mb-4 bold">
                                    Numaligarh, Assam- 785699<br>General Nursing and Midwifery<br>
                                    Session {{$application->session->name}}
                                </p>
                            </div>
                        </td>
                        <td width="20%" class="padding-xs">
                        @if ($application->passport_photo())
                        <img width="70" style="max-width: 70px;"
                        class="avatar avatar-xxl"
                        {{-- src="{{route("common.download.image", [$application->student_id, $application->id, $application->passport_photo()->file_name])}}"> --}}
                        src="{{base_path("public/uploads/".$application->student_id."/".$application->id."/".$application->passport_photo()->file_name)}}">
                        @else
                            Passport Photo Not Uploaded
                        @endif
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <h4>Personal Details</h4>
                <table class="table table-bordered"  width="100%" border="1" style="border-collapse: collapse">
                    <tbody>
                        {{-- <tr>
                            <td class="padding-xs bold" colspan="4">Personal Details</td>
                        </tr> --}}
                        <tr>
                            <td class="padding-xs">Registration Number</td>
                            <td class="padding-xs bold">{{$application->student_id}}</td>
                            <td class="padding-xs">Application Number</td>
                            <td class="padding-xs bold">{{$application->id}}</td>
                        </tr>
                        <tr>
                            <td class="padding-xs">Permanent&nbsp;Add.</td>
                            <td class="padding-xs bold">
                                @if(getApplicationPermanentAddress($application))
                                    Vill/Town:  {{$application->permanent_village_town}} </br> 
                                    PS: {{$application->permanent_ps}} </br>
                                    PO: {{$application->permanent_po}} </br>
                                    Dist: {{$application->permanent_district}} </br>
                                    State: {{$application->permanent_state}} -  {{$application->permanent_pin}}
                                @else
                                    NA
                                @endif
                            </td>
                            <td class="padding-xs">Correspondence&nbsp;Add.</td>
                            <td class="padding-xs bold">
                                @if(getCorrespondencePermanentAddress($application))
                                    Vill/Town: {{$application->correspondence_village_town}}</br>
                                    PS: {{$application->correspondence_ps}} </br>
                                    PO: {{$application->correspondence_po}} </br>
                                    Dist: {{$application->correspondence_district}} </br>
                                    State: {{$application->correspondence_state}} - {{$application->correspondence_pin}}
                                @else
                                    NA
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="padding-xs">Nationality</td>
                            <td class="padding-xs bold">{{$application->nationality}}</td>
                            <td class="padding-xs">Marital Status</td>
                            <td class="padding-xs bold">{{$application->marital_status}}</td>
                        </tr>
                        <tr>
                            <td class="padding-xs">Full Name</td>
                            <td class="padding-xs bold">{{$application->fullname  ?? "NA"}}</td>
                            <td class="padding-xs">Date of Birth</td>
                            <td class="padding-xs bold">{{dateFormat($application->dob, "d-m-Y")  ?? "NA"}}</td>
                            
                        </tr>
                        <tr>
                            <td class="padding-xs">Gender</td>
                            <td class="padding-xs bold ">{{$application->gender  ?? "NA"}}</td>
                            <td class="padding-xs">Religion</td>
                            <td class="padding-xs bold">{{$application->religion  ?? "NA"}}</td>
                        </tr>
                        {{-- <tr>
                            <td class="padding-xs">Date of Birth</td>
                            <td class="padding-xs bold">{{dateFormat($application->dob, "d-m-Y")}}</td>
                            <td class="padding-xs" colspan="2"></td>
                            <td class="padding-xs bold"></td>
                        </tr> --}}
                        <tr>
                            <td class="padding-xs">Father's Name</td>
                            <td class="padding-xs bold">{{$application->father_name ?? "NA"}}</td>
                            <td class="padding-xs">Occupation</td>
                            <td class="padding-xs bold">{{$application->father_occupation ?? "NA"}}</td>
                        </tr>
                        <tr>
                            <td class="padding-xs">Mother's Name</td>
                            <td class="padding-xs bold">{{$application->mother_name ?? "NA"}}</td>
                            <td class="padding-xs">Occupation</td>
                            <td class="padding-xs bold">{{$application->mother_occupation ?? "NA"}}</td>
                        </tr>
                        <tr>
                            <td class="padding-xs">Caste</td>
                            <td class="padding-xs bold">
                                {{$application->caste->name ?? "NA"}}
                                <strong>{{$application->sub_cat ? "($application->sub_cat)" : ""}}</strong>
                            </td>
                            <td class="padding-xs">Differently Abled</td>
                            <td class="padding-xs bold">
                                {{$application->differently_abled==0?'No':'Yes'}}
                            </td>
                        </tr>
                        <tr>
                            <td class="padding-xs">ANM/LHV</td>
                            <td class="padding-xs bold">{{$application->anm_or_lhv==0?'No':'Yes'}}</td>
                            <td class="padding-xs">Registration NO (ANM/LHV)</td>
                            <td class="padding-xs bold">
                                {{$application->anm_or_lhv_registration}}
                            </td>
                        </tr>
                        <tr>
                            <td class="padding-xs">Contact Number</td>
                            <td class="padding-xs bold">{{$application->student->mobile_no ?? "NA"}}</td>
                            <td class="padding-xs">E-mail</td>
                            <td class="padding-xs bold">{{$application->student->email ?? "NA"}}</td>
                        </tr>
                    </tbody>
                </table>
                <h4>Academic Details</h4>
                <table class="table table-bordered"  width="100%" border="1" style="border-collapse: collapse">
                    <thead>
                        <tr>
                            <th>Exam</th>
                            <th>Stream</th>
                            <th>Year of Passing</th>
                            <th>Board/ Council</th>
                            <th>Name of the school from where passed</th>
                            <th>Subjects Appeared</th>
                            <th>Marks Obtained</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>10<sup>th</sup></th>
                            <td>{{$application->academic_10_stream ?? "NA"}}</td>
                            <td>{{$application->academic_10_year ?? "NA"}}</td>
                            <td>{{$application->academic_10_board ?? "NA"}}</td>
                            <td>{{$application->academic_10_school ?? "NA"}}</td>
                            <td>{{$application->academic_10_subject ?? "NA"}}</td>
                            <td>{{$application->academic_10_mark ?? "NA"}}</td>
                            <td>{{$application->academic_10_percentage ?? "NA"}}</td>
                        </tr>
                        <tr>
                            <th>12<sup>th</sup></th>
                            <td>{{$application->academic_12_stream ?? "NA"}}</td>
                            <td>{{$application->academic_12_year ?? "NA"}}</td>
                            <td>{{$application->academic_12_board ?? "NA"}}</td>
                            <td>{{$application->academic_12_school ?? "NA"}}</td>
                            <td>{{$application->academic_12_subject ?? "NA"}}</td>
                            <td>{{$application->academic_12_mark ?? "NA"}}</td>
                            <td>{{$application->academic_12_percentage ?? "NA"}}</td>
                        </tr>
                        <tr>
                            <th>Vocational</th>
                            <td>{{$application->academic_voc_stream ?? "NA"}}</td>
                            <td>{{$application->academic_voc_year ?? "NA"}}</td>
                            <td>{{$application->academic_voc_board ?? "NA"}}</td>
                            <td>{{$application->academic_voc_school ?? "NA"}}</td>
                            <td>{{$application->academic_voc_subject ?? "NA"}}</td>
                            <td>{{$application->academic_voc_mark ?? "NA"}}</td>
                            <td>{{$application->academic_voc_percentage ?? "NA"}}</td>
                        </tr>
                        <tr>
                            <th>ANM</th>
                            <td>{{$application->academic_anm_stream ?? "NA"}}</td>
                            <td>{{$application->academic_anm_year ?? "NA"}}</td>
                            <td>{{$application->academic_anm_board ?? "NA"}}</td>
                            <td>{{$application->academic_anm_school ?? "NA"}}</td>
                            <td>{{$application->academic_anm_subject ?? "NA"}}</td>
                            <td>{{$application->academic_anm_mark ?? "NA"}}</td>
                            <td>{{$application->academic_anm_percentage ?? "NA"}}</td>
                        </tr>
                        <tr>
                            <td colspan="2">Other Qualification</td>
                            <td colspan="3"> {{$application->other_qualification}}</td>
                            <td colspan="2" class="text-right">English mark obtained in 12<sup>th</sup></td>
                            <td class="text-left"> {{$application->english_mark_obtain}}</td>
                        </tr>
                    </tbody>
                </table>
                
                <table class="table"  width="100%">
                    <tbody>
                        <tr>
                            <td>I hereby, declare that the above statements provided by me are true and complete to the best of my
                knowledge.
                If any
                information is found to be incorrect or untrue at any stage, I shall be liable for the same and have no
                claim
                against the cancellation and /or taking any other legal action as deemed fit by the authority.<td></tr>
                        <tr>
                            <td class="text-right" style="text-align:right;">
                                    @if ($application->signature())
                                        <img  style="width: 160px; height: 60px; max-width: 160px; max-height: 60px;"
                                    {{-- src="{{route("common.download.image", [$application->student_id, $application->id, $application->signature()->file_name])}}"></td> --}}
                                    src="{{base_path("public/uploads/".$application->student_id."/".$application->id."/".$application->signature()->file_name)}}">
                                    @else
                                        Signature Not Uploaded
                                    @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
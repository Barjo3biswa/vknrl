<style>
    .bold {
        font-weight: bold;
    }
</style>

<div class="container" id="page-content">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title" style="padding-bottom: 5px;">Application Details @if($application->status == "payment_done")<span class="pull-right dont-print" style="margin-bottom:2px;"><a
                            href="{{request()->fullUrlWithQuery(['download-pdf' => 1])}}"><button class="btn btn-sm btn-default"><i
                                    class="fa fa-download"></i> Download</button></a></span> @endif</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <table width="100%">
                        <tbody>
                            <tr class="text-center">
                                <td width="20%" class="padding-xs">
                                    <img style="max-width: 80px" width="80" class="avatar avatar-xxl"
                                        src="{{asset_public('logo.jpg')}}">
                                </td>
                                <td class="padding-xs">
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
                                    <img width="80" style="max-width: 80px;" class="avatar avatar-xxl"
                                        src="{{route("common.download.image", [$application->student_id, $application->id, $application->passport_photo()->file_name])}}">
                                    @else
                                    Passport Photo Not Uploaded
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-xs-12 table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="padding-xs bold" colspan="4">Personal Details</td>
                                </tr>
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
                                        {{-- {!!getCorrespondencePermanentAddress($application) ?? "NA"!!} --}}
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
                                    <td class="padding-xs bold">{{$application->caste->name ?? "NA"}} <strong>{{$application->sub_cat ? "($application->sub_cat)" : ""}}</strong></td>
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
                        <table class="table table-bordered">
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
                        @if($application->attachment_others()->count())
                        <table class="table table-bordered table-attachments">
                            <caption>
                                <h4><strong>Attachments</strong></h4>
                            </caption>
                            <tr>
                                {{-- printing heading --}}
                                @forelse ($application->attachment_others() as $attachment)
                                <td>
                                    <a
                                        href="{{route("common.download.image", [$application->student_id, $application->id, $attachment->file_name])}}">
                                        {{str_replace(["Disablity", "Anm", "Prc", "Bpl"], ["Disability", "ANM", "PRC", "BPL"], ucwords(str_replace("_"," ",$attachment->doc_name)))}}
                                    </a>
                                </td>
                                @empty
                                <td class="text-danger">No Attachment Found</td>
                                @endforelse
                            </tr>
                        </table>
                        @endif
                        <table class="table" border="0">
                            <tbody>
                                <tr>
                                    <td class="text-right">
                                        @if ($application->passport_photo())
                                        <img style="width: 160px; height: 60px; max-width: 160px; max-height: 60px;"
                                            src="{{route("common.download.image", [$application->student_id, $application->id, $application->signature()->file_name])}}">
                                    </td>
                                    @else
                                    Signature Not Uploaded
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</div>
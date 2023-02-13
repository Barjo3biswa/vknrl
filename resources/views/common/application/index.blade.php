<div class="table-responsive">
<table class="table table-bordered table-striped" id="myTable">
    <thead>
        <tr>
            @if (auth("admin")->check())
            <th><label class="checkbox-inline"><input type="checkbox" id="select-all"
                value="">Select All</label></th>
            @endif
            <th>#</th>
            <th>Registration No</th>
            <th>Application No</th>
            <th>Applicant Name</th>
            <th>Caste</th>
            <th>Gender</th>
            <th>Status</th>
            <th>Action</th>
            @if (auth("admin")->check())
                <th>Admin Action</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse ($applications as $key => $application)
        <tr id="row_{{$application->id}}">
            @if (auth("admin")->check())
                <td><label class="checkbox-inline"><input type="checkbox" class="check"
                    name="application_ids[]" value="{{$application->id}}">&nbsp;</label>
                </td>
            @endif
            <td>{{ $key+ 1 + ($applications->perPage() * ($applications->currentPage() - 1)) }}</td>
            <td>{{$application->student->id}}</td>
            <td>{{$application->id}}</td>
            <td>{{$application->fullname}}</td>
            <td>{{$application->caste->name}} 
                @if($application->sub_cat && $application->sub_cat != "NA")
                    <strong>({{$application->sub_cat}})</strong>
                @endif
            </td>
            <td>{{$application->gender}}</td>
            <td id="status">
                @if($application->form_step <= 4 && $application->status == "application_submitted")
                    @if($application->form_step < 4)
                        Step {{$application->form_step}} is Completed
                    @else
                        Waiting for Final Review (Applicant)
                    @endif
                @else
                    @if(in_array($application->status, ["on_hold", "rejected"]))
                        <span class="text-danger" data-toggle="tooltip" data-title="{{($application->reject_reason ? $application->reject_reason : $application->hold_reason)}}">
                            {{ucwords(str_replace("_"," ", $application->status))}} <br>
                            ({{($application->reject_reason ? $application->reject_reason : $application->hold_reason)}})
                        </span>
                    @elseif($application->admit_card_published && $application->status == "accepted" )
                        {{"Admit Card Available"}}
                    @else
                        {{ucwords(str_replace("_"," ", $application->status))}}
                    @endif
                @endif
            </td>
            <td>
                @if(auth("admin")->check())
                    {{-- admin conditional button --}}
                    <a href="{{route("admin.application.show", Crypt::encrypt($application->id))}}"><button type="button" class="btn btn-primary btn-sm">View</button></a>
                    @if(request()->has("show_edit_option"))
                        <a href="{{route("admin.application.edit", Crypt::encrypt($application->id))}}"><button type="button" class="btn btn-warning btn-sm">Edit</button></a>
                    @endif
                @elseif(auth("student")->check())
                    <a href="{{route("student.application.show", Crypt::encrypt($application->id))}}"><button type="button" class="btn btn-primary btn-sm">View</button></a>
                    @if($application->form_step <= 4 && $application->status == "application_submitted")
                        <a href="{{route("student.application.edit", Crypt::encrypt($application->id))}}"><button type="button" class="btn btn-warning btn-sm">Edit</button></a>
                    @endif
                    @if(!$application->payment_status && $application->form_step >=4 && $application->status == "payment_pending")
                        <a href="{{route("student.application.process-payment", Crypt::encrypt($application->id))}}"><button type="button" class="btn btn-warning btn-sm">Go for Payment</button></a>
                    @endif
                    @if($application->form_step == 4 && $application->status == "application_submitted")
                        <a href="{{route("student.application.final-submit", Crypt::encrypt($application->id))}}"><button type="button" class="btn btn-danger btn-sm" onclick="return confirm('You are proceeding for “Payment & Final Submission”.  Modification will not be allowed. \nContinue ?')">Payment & Final Submission</button></a>
                    @endif

                @endif
                @if($application->payment_status)
                    <a href="{{route(get_guard().".application.payment-reciept", Crypt::encrypt($application->id))}}" target="_blank"><button type="button" class="btn btn-success btn-sm">Payment Receipt</button></a>
                    @if($application->admit_card_published)
                        @if(auth("admin")->check())
                            <a href="{{route("admin.admit-card.download.pdf", Crypt::encrypt($application->admit_card_published->id))}}" target="_blank"><button type="button" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-download"></i> Admit Card</button></a>
                        @elseif(auth("student")->check())
                            <a href="{{route("student.admit-card.download", Crypt::encrypt($application->id))}}" target="_blank"><button type="button" class="btn btn-danger  btn-sm"> Admit Card</button></a>
                        @endif
                    @endif
                @endif
            </td>
            @if (auth("admin")->check())
                <td id="admin_buttons">
                    @if ($application->status == 'payment_done')
                        <data style="display:none" data-application="{{json_encode(collect($application->only(['fullname', 'anm_or_lhv', 'english_mark_obtain']))->merge(['caste' => $application->caste->name, "application_no" => $application->id]))}}"></data>
                        <button type="button" class="btn btn-sm btn-primary" onclick="verfiyApplication(this)" data-application-id="{{Crypt::encrypt($application->id)}}">Accept</button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="rejectApplication(this)" data-application-id="{{Crypt::encrypt($application->id)}}">Reject</button>
                        <button type="button" class="btn btn-sm btn-warning" onclick="holdApplication(this)" data-application-id="{{Crypt::encrypt($application->id)}}">On Hold</button>
                    @elseif($application->status == 'on_hold')
                        <data style="display:none" data-application="{{json_encode(collect($application->only(['fullname', 'anm_or_lhv', 'english_mark_obtain']))->merge(['caste' => $application->caste->name, "application_no" => $application->id]))}}"></data>
                        <button type="button" class="btn btn-sm btn-primary" onclick="verfiyApplication(this)" data-application-id="{{Crypt::encrypt($application->id)}}">Accept</button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="rejectApplication(this)" data-application-id="{{Crypt::encrypt($application->id)}}">Reject</button>
                    @endif
                </td>
            @endif
        </tr>
        @empty
        <tr>
            <td class="text-danger text-center" colspan="7">No Records found</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{$applications->appends(request()->all())->links()}}
</div>
@php
    $disable_anm  = "";
    if(isset($application)){
        if(!$application->anm_or_lhv){
            $disable_anm = "disabled";
        }
    }
@endphp
<div class="step" class="certificate">
    <div class="row">
        <div class="col-sm-12">
            @if($application->attachments->count())
                <table class="table table-bordered table-attachments">
                    <caption><h4><strong>Uploaded Attachments</strong></h4></caption>
                    <tr>
                        {{-- printing heading --}}
                        @forelse ($application->attachments as $attachment)
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
        </div>
    </div>
    {{-- Certificate --}}
    <fieldset id="certificate">
        <legend>Certificates: <span class="pull-right text-danger">(Final Step)</span></legend>
        <div class="col-md-12 col-lg-12">
            <div class="alert alert-warning">
                <strong><i class="fa fa-exclamation-triangle"></i></strong> File Size Maximum Limit 1MB (except passport
                photo and signature)
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="passport_photo" class="col-md-4 control-label text-right margin-label">Passport Photo:
                        <span class="text-danger">* max 200px(W) X max 250px(H) and max size 100KB</span></label>
                    <div class="col-md-7">
                        <input type="file" name="passport_photo" class="form-control" placeholder="Passport Photo"
                            @if (isset($application) && $application->form_step < 4)
                                required
                            @endif
                            accept="image/jpeg, image/png">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="signature" class="col-md-4 control-label text-right margin-label">Signature:
                        <span class="text-danger">* max 200px(W) X max 150px(H) and max size 100KB</span></label>
                    <div class="col-md-7">
                        <input type="file" name="signature" class="form-control" placeholder="Signature"
                            accept="image/jpeg,image/png"
                            @if (isset($application) && $application->form_step < 4)
                                required
                            @endif
                        >
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="prc_certificate" class="col-md-4 control-label text-right margin-label">PRC Certificate:
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="file" name="prc_certificate" class="form-control"
                            placeholder="PRC Certificate" accept="image/jpeg,image/png,application/pdf"
                            @if (isset($application) && $application->form_step < 4)
                                required
                            @endif
                        >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="age_proof_certificate" class="col-md-4 control-label text-right margin-label">Age Proof
                        Certificate:
                        <span class="text-danger">* (Matriculation admit/ Birth Certificate)</span></label>
                    <div class="col-md-7">
                        <input type="file" name="age_proof_certificate" class="form-control"
                            @if (isset($application) && $application->form_step < 4)
                                required
                            @endif
                            placeholder="Age Proof Certificate" accept="image/jpeg,image/png,application/pdf">
                    </div>
                </div>
            </div>
        </div>
        @php
            $caste_required = "";
            if(isset($application)){
                if(strtolower($application->caste->name) != "general" || $application->sub_cat){
                    $caste_required = "required";
                    if($application->attachments->where("doc_name", "caste_certificate")->count()){
                        $caste_required = "";
                    }
                }
                if(strtolower($application->caste->name) == "general"){
                    $caste_required = "disabled";
                }
            }
        @endphp
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="caste_certificate" class="col-md-4 control-label text-right margin-label">Caste
                        Certificate:
                        <span class="text-danger"> (NCL incase of OBC/MOBC, EWS incase of EWS)</span></label>
                    <div class="col-md-7">
                        <input type="file" name="caste_certificate" class="form-control" placeholder="Caste Certificate"
                            accept="image/jpeg,image/png,application/pdf" {{$caste_required}}>
                    </div>
                </div>
            </div>
            @php
                $disablity_certificate_required = "";
                if(isset($application)){
                    $disablity_certificate_required = "required";
                    if(!$application->person_with_disablity){
                        $disablity_certificate_required = "disabled";
                    }
                    if($application->attachments->where("doc_name", "disablity_certificate")->count()){
                        $disablity_certificate_required = "";
                    }
                }    
            @endphp
            <div class="col-md-6">
                <div class="form-group">
                    <label for="disablity_certificate" class="col-md-4 control-label text-right margin-label">Disability
                        Certificate:
                        <span class="text-danger">(if PWD)</span></label>
                    <div class="col-md-7">
                        <input type="file" name="disablity_certificate" class="form-control"
                            placeholder="Disability Certificate" accept="image/jpeg,image/png,application/pdf" {{$disablity_certificate_required}}>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="document_mentioning_name_of_the_school_class_10"
                        class="col-md-4 control-label text-right margin-label">Document mentioning name of the school
                        from where class 10 appeared
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="file" name="document_mentioning_name_of_the_school_class_10" class="form-control"
                            placeholder="Document mentioning name of the school"
                            accept="image/jpeg,image/png,application/pdf"
                            @if (isset($application) && $application->form_step < 4)
                                required
                            @endif
                        >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="12_marksheet" class="col-md-4 control-label text-right margin-label">10+2 / equivalent
                        examination qualifying <strong>marksheet</strong>
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="file" name="12_marksheet" class="form-control"
                            placeholder="Disability Certificate" accept="image/jpeg,image/png,application/pdf"
                            @if (isset($application) && $application->form_step < 4)
                                required
                            @endif
                        >
                    </div>
                </div>
            </div>
        </div>
        @php
            $anm_required = $anm_required_2 = "";
            if(isset($application)){
                if(checkAnmDataEntered($application) || $application->anm_or_lhv){
                    $anm_required = $anm_required_2 = "required";
                }
                if($application->attachments->where("doc_name", "anm_registration")->count()){
                    $anm_required = "";
                }
                if($application->attachments->where("doc_name", "anm_marksheet")->count()){
                    $anm_required_2 = "";
                }
            }
        @endphp
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="anm_registration" class="col-md-4 control-label text-right margin-label">ANM
                        registration
                        <span class="text-danger">(only if ANM qualified)</span></label>
                    <div class="col-md-7">
                        <input type="file" name="anm_registration" class="form-control" {{$anm_required}} {{$disable_anm}}
                            placeholder="ANM Registration" accept="image/jpeg,image/png,application/pdf">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="anm_marksheet" class="col-md-4 control-label text-right margin-label">ANM marksheet
                        <span class="text-danger">(only if ANM qualified)</span></label>
                    <div class="col-md-7">
                        <input type="file" name="anm_marksheet" class="form-control"
                            placeholder="ANM Marksheet" accept="image/jpeg,image/png,application/pdf" {{$anm_required_2}} {{$disable_anm}}>
                    </div>
                </div>
            </div>
        </div>
        @php
            // $bpl_required = "";
            // if(isset($application) && $application->bpl){
            //     $bpl_required = "required";
            //     if($application->attachments->where("doc_name", "bpl_document")->count()){
            //         $bpl_required = "";
            //     }
            // }
        @endphp
        {{-- <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="bpl_document" class="col-md-4 control-label text-right margin-label">BPL supporting document
                        <span class="text-danger">(If BPL)</span></label>
                    <div class="col-md-7">
                        <input type="file" name="bpl_document" class="form-control" {{$bpl_required}}
                            placeholder="BPL supporting document" accept="image/jpeg,image/png,application/pdf">
                    </div>
                </div>
            </div>
        </div> --}}
    </fieldset>

    <div class="row">
        <div class="col-sm-12">
            <label class="checkbox-inline"><input type="checkbox" name="accept" id="accept" value="1"
                    required>Accept</label>
            <p>I hereby, declare that the above statements provided by me are true and complete to the best of my
                knowledge.
                If any
                information is found to be incorrect or untrue at any stage, I shall be liable for the same and have no
                claim
                against the cancellation and /or taking any other legal action as deemed fit by the authority.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-right">
            <button type="submit" class="btn btn-md btn-success disabled" id="submit_btn">Submit & Update <i
                    class="fa fa-send"></i></button>
        </div>
    </div>
</div>
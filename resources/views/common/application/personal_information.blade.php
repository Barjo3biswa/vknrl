{{-- Personal Information --}}
<div class="step" class="personal_information">
    <fieldset id="personal_information">
        <legend>Personal Information <span class="pull-right text-danger">(Step 1)</span></legend>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="fullname" class="col-md-4 control-label text-right margin-label">Full Name: <span
                            class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="text" name="fullname" class="form-control" required placeholder="Full Name"
                            @isset($application) value="{{old("fullname",$application->fullname)}}" @else
                            value="{{old("fullname",auth()->guard("student")->user()->name)}}" @endisset>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="gender" class="col-md-4 control-label text-right margin-label">Gender: <span
                            class="text-danger">(*
                            Female Candidate Only.)</span></label>
                    <div class="col-md-7">
                        {{-- <select name="gender" id="gender" class="form-control" required>
                            <option value="" selected disabled>--SELECT--</option>
                            <option value="Female" 
                            @if (isset($application) && old("gender", $application->gender) == "Female")
                                selected
                                @endif
                                >Female</option>
                        </select> --}}
                        <input type="text" name="gender" class="form-control" required placeholder="Gender"
                        value="Female" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="father_name" class="col-md-4 control-label text-right margin-label">Father Name: <span
                            class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="text" name="father_name" class="form-control" required placeholder="Father Name"
                            @isset($application) value="{{old("father_name", $application->father_name)}}" @else
                            value="{{old("father_name")}}" @endif>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="father_occupation" class="col-md-4 control-label text-right margin-label">Father
                        Occupation:
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="text" name="father_occupation" class="form-control" required
                            placeholder="Father Occupation" @isset($application)
                            value="{{old("father_occupation", $application->father_occupation)}}" @else
                            value="{{old("father_occupation")}}" @endif>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="mother_name" class="col-md-4 control-label text-right margin-label">Mother Name: <span
                            class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="text" name="mother_name" class="form-control" required placeholder="Mother Name"
                            @isset($application) value="{{old("mother_name", $application->mother_name)}}" @else
                            value="{{old("mother_name")}}" @endif>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="mother_occupation" class="col-md-4 control-label text-right margin-label">Mother
                        Occupation:
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="text" name="mother_occupation" class="form-control" required
                            placeholder="Mother Occupation"
                            @isset($application) value="{{old("mother_occupation", $application->mother_occupation)}}" @else
                            value="{{old("mother_occupation")}}" @endif
                        >
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label for="maritial_status" class="col-md-4 control-label text-right margin-label">Marital status:
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <select name="maritial_status" id="maritial_status" class="form-control" required>
                            <option value="" selected disabled>--SELECT--</option>
                            <option value="Married"
                            @if(isset($application) && old("maritial_status", $application->marital_status) == "Married")
                                selected
                            @else
                            {{(old("marital_status") == "Married")}}
                            @endif
                            >Married</option>
                            <option value="Unmarried"
                            @if(isset($application) && old("maritial_status", $application->marital_status) == "Unmarried")
                                selected
                            @else
                            {{(old("marital_status") == "Unmarried")}}
                            @endif
                            >Unmarried</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="religion" class="col-md-4 control-label text-right margin-label">Religion: <span
                            class="text-danger">*</span></label>
                    <div class="col-md-7">
                        {{-- <input type="text" name="religion" class="form-control" required placeholder="Religion"
                        @isset($application) value="{{old("religion", $application->religion)}}" @else
                            value="{{old("religion")}}" @endif
                        > --}}
                        <select name="religion" id="religion" class="form-control" placeholder="Religion" required>
                                Hindu/Muslim/Christian/Sikh/Others
                                <option value="" disabled selected>--SELECT--</option>
                                <option value="Hindu"
                                @isset($application)
                                    {{old("religion", $application->religion) == "Hindu" ? "selected" : ""}}
                                @else
                                    {{old("religion") == "Hindu" ? "selected" : ""}}
                                @endif
                                >Hindu</option>
                                <option value="Muslim"
                                @isset($application)
                                    {{old("religion", $application->religion) == "Muslim" ? "selected" : ""}}
                                @else
                                    {{old("religion") == "Muslim" ? "selected" : ""}}
                                @endif
                                >Muslim</option>
                                <option value="Sikh"
                                @isset($application)
                                    {{old("religion", $application->religion) == "Sikh" ? "selected" : ""}}
                                @else
                                    {{old("religion") == "Sikh" ? "selected" : ""}}
                                @endif
                                >Sikh</option>
                                <option value="Buddhism"
                                @isset($application)
                                    {{old("religion", $application->religion) == "Buddhism" ? "selected" : ""}}
                                @else
                                    {{old("religion") == "Buddhism" ? "selected" : ""}}
                                @endif
                                >Buddhism</option>
                                <option value="Other"
                                @isset($application)
                                    {{old("religion", $application->religion) == "Other" ? "selected" : ""}}
                                @else
                                    {{old("religion") == "Other" ? "selected" : ""}}
                                @endif
                                >Other</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label for="nationality" class="col-md-4 control-label text-right margin-label">Nationality: <span
                            class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="text" name="nationality" class="form-control" required placeholder="Nationality"
                        value="Indian" readonly>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="dob" class="col-md-4 control-label text-right margin-label">Date of Birth: <span
                            class="text-danger">* (Age limit 17 to 35)</span></label>
                    <div class="col-md-7">
                        <input type="text" name="dob" class="form-control zebra" id="dob" required placeholder="Date of Birth"
                        @isset($application) value="{{old("dob", dateFormat($application->dob))}}" @else
                            value="{{old("dob")}}" @endif
                        >
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="caste" class="col-md-4 control-label text-right margin-label">Caste: <span
                            class="text-danger">*</span></label>
                    <div class="col-md-7">
                        {{-- <input type="text" name="caste" class="form-control" required placeholder="Nationality"> --}}
                        <select name="caste" id="caste" class="form-control" required>
                            <option value="" selected disabled>--SELECT--</option>
                            @foreach ($castes as $caste)
                            <option value="{{$caste->id}}"
                                @isset($application) 
                                {{old("caste", $application->caste_id) == $caste->id ? "selected" : ""}} 
                                @else
                                    {{(old("caste") == $caste->id) ? "selected" : ""}}
                                @endif
                            >{{$caste->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="age" class="col-md-4 control-label text-right margin-label">Age:
                        <span class="text-danger">* (as on 31-12-2022)</span></label>
                    <div class="col-md-7">
                        <input type="number" class="form-control" required name="age" readonly id="age" min="17" max="35"
                        @if(isset($application))
                            value="{{findDateDiff($application->dob, \App\Models\Application::$dob_compare_to)["years"]}}"
                        @else
                            value="0"
                        @endif
                        >
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group ews">
                    <label for="sub_cat" class="col-md-4 control-label text-right margin-label"> Belongs to EWS:
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <label class="radio-inline"><input type="radio" value="EWS" name="sub_cat" id="sub_cat" required
                            @isset($application) 
                                {{old("sub_cat", $application->sub_cat) == "EWS" ? "checked" : ""}} 
                            @else
                                {{(old("sub_cat") == "EWS") ? "checked" : ""}}
                            @endif
                            > <span
                                class="text-warning">Yes</span></label>
                        <label class="radio-inline"><input type="radio" value="NA" name="sub_cat" id="sub_cat" required
                            @isset($application) 
                                @if($application->caste_id == 1)
                                    {{old("sub_cat", $application->sub_cat) == "NA" ? "checked" : ""}} 
                                @endif
                            @else
                                {{(old("sub_cat") == "NA") ? "checked" : "checked"}}
                            @endif
                        >
                            <span class="text-warning">No</span></label>
                    </div>
                </div>
                <div class="form-group ncl">
                    <label for="sub_cat" class="col-md-4 control-label text-right margin-label"> Belongs to Non Creamy Layer:
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <label class="radio-inline"><input type="radio" value="NCL" name="sub_cat" id="sub_cat" required
                            @isset($application) 
                                {{old("sub_cat", $application->sub_cat) == "NCL" ? "checked" : ""}} 
                            @else
                                {{(old("sub_cat") == "NCL") ? "checked" : ""}}
                            @endif
                            > <span
                                class="text-warning">Yes</span></label>
                        <label class="radio-inline"><input type="radio" value="NA" name="sub_cat" id="sub_cat" required
                            @isset($application) 
                                @if($application->caste_id == 4)
                                    {{old("sub_cat", $application->sub_cat) == "NA" ? "checked" : ""}} 
                                @endif
                            @else
                                {{(old("sub_cat") == "NA") ? "checked" : "checked"}}
                            @endif
                        >
                            <span class="text-warning">No</span></label>
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-6">
                <div class="form-group">
                    <label for="disablity" class="col-md-4 control-label text-right margin-label">Belongs to BPL:
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <label class="radio-inline"><input type="radio" value="1" name="bpl" id="bpl"
                            
                            @isset($application) 
                                {{old("bpl", $application->bpl) == 1 ? "checked" : ""}} 
                            @else
                                {{(old("bpl") == 1) ? "checked" : ""}}
                            @endif
                            > <span class="text-warning" >Yes</span></label>
                        <label class="radio-inline"><input type="radio" value="0" name="bpl" id="bpl"
                                @isset($application) 
                                    {{old("bpl", $application->bpl) == 0 ? "checked" : ""}} 
                                @else
                                    {{(old("bpl") == 0) ? "checked" : "checked"}}
                                @endif
                                >
                            <span class="text-warning">No</span></label>
                    </div>
                </div>
            </div> --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label for="anm_or_lhv" class="col-md-4 control-label text-right margin-label">ANM/LHV Registered ?:
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        {{-- <input type="text" name="disablity" class="form-control" required
                            placeholder="Person with Disability"> --}}
                        <label class="radio-inline"><input type="radio" class="anm_or_lhv" value="1" name="anm_or_lhv" id="anm_or_lhv"
                            @isset($application) 
                                {{old("anm_or_lhv", $application->anm_or_lhv) == 1 ? "checked" : ""}} 
                            @else
                                {{(old("anm_or_lhv") == 1) ? "checked" : ""}}
                            @endif
                            > <span
                                class="text-warning">Yes</span></label>
                        <label class="radio-inline"><input type="radio" value="0" class="anm_or_lhv" name="anm_or_lhv" id="anm_or_lhv"
                            @isset($application) 
                                {{old("anm_or_lhv", $application->anm_or_lhv) == 0 ? "checked" : ""}} 
                            @else
                                {{(old("anm_or_lhv") == 0) ? "checked" : "checked"}}
                            @endif
                        >
                            <span class="text-warning">No</span></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="disablity" class="col-md-4 control-label text-right margin-label">Person with
                        Disability:
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        {{-- <input type="text" name="disablity" class="form-control" required
                            placeholder="Person with Disability"> --}}
                        <label class="radio-inline"><input type="radio" value="1" name="disablity" id="disablity"
                            @isset($application) 
                                {{old("disablity", $application->person_with_disablity) == 1 ? "checked" : ""}} 
                            @else
                                {{(old("disablity") == 1) ? "checked" : ""}}
                            @endif
                            > <span
                                class="text-warning">Yes</span></label>
                        <label class="radio-inline"><input type="radio" value="0" name="disablity" id="disablity"
                            @isset($application) 
                                {{old("disablity", $application->person_with_disablity) == 0 ? "checked" : ""}} 
                            @else
                                {{(old("disablity") == 0) ? "checked" : "checked"}}
                            @endif
                        >
                            <span class="text-warning">No</span></label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="age" class="col-md-4 control-label text-right margin-label">Registration No:
                        <span class="text-danger">* (Provided by nursing council)</span></label>
                    <div class="col-md-7">
                        <input type="text" class="form-control" placeholder="Registration No" required name="anm_or_lhv_registration" id="anm_or_lhv_registration"
                        @if(isset($application))
                            value="{{$application->anm_or_lhv_registration}}" data-old="{{$application->anm_or_lhv_registration}}" {{$application->anm_or_lhv ? "" :" readonly "}}
                        @else
                            value="NA" readonly
                        @endif
                        >
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="row">
        <div class="col-xs-12 text-right">
            <button type="submit" class="btn btn-success next" id="next">Submit & Next <i class="fa fa-save"></i></button>
        </div>
    </div>
</div>
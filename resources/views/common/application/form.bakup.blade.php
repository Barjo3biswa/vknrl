<div class="btn-group btn-group-justified form-nav">
    <a href="#correspondense_address" class="btn btn-default">Correspondence Address</a>
    <a href="#permanent_address" class="btn btn-default">Permanent Address</a>
    <a href="#academic_details" class="btn btn-default">Academic Details</a>
    <a href="#certificate" class="btn btn-default">Certificate Upload</a>
</div>
<br>
<br>
{{ csrf_field() }}
{{-- Personal Information --}}
<fieldset id="personal_information">
    <legend>Personal Information</legend>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="fullname" class="col-md-4 control-label text-right margin-label">Full Name: <span
                        class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="fullname" class="form-control" required placeholder="Full Name"
                        value="{{auth()->guard("student")->user()->name}}">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="gender" class="col-md-4 control-label text-right margin-label">Gender: <span
                        class="text-danger">(*
                        Female Candidate Only.)</span></label>
                <div class="col-md-7">
                    <select name="gender" id="gender" class="form-control" required>
                        <option value="" selected disabled>--SELECT--</option>
                        <option value="Female">Female</option>
                    </select>
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
                    <input type="text" name="father_name" class="form-control" required placeholder="Father Name">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="father_occupation" class="col-md-4 control-label text-right margin-label">Father Occupation:
                    <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="father_occupation" class="form-control" required
                        placeholder="Father Occupation">
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
                    <input type="text" name="mother_name" class="form-control" required placeholder="Mother Name">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="mother_occupation" class="col-md-4 control-label text-right margin-label">Mother Occupation:
                    <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="mother_occupation" class="form-control" required
                        placeholder="Mother Occupation">
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
                        <option value="Married">Married</option>
                        <option value="Unmarried">Unmarried</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="religion" class="col-md-4 control-label text-right margin-label">Religion: <span
                        class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="religion" class="form-control" required placeholder="Religion">
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
                    <input type="text" name="nationality" class="form-control" required placeholder="Nationality">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="dob" class="col-md-4 control-label text-right margin-label">Date of Birth: <span
                        class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="dob" class="form-control zebra" required placeholder="Date of Birth">
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
                        <option value="{{$caste->id}}">{{$caste->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6">
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="disablity" class="col-md-4 control-label text-right margin-label">Person with Disability:
                    <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    {{-- <input type="text" name="disablity" class="form-control" required
                        placeholder="Person with Disability"> --}}
                    <label class="radio-inline"><input type="radio" value="1" name="disablity" id="disablity"> <span
                            class="text-warning">Yes</span></label>
                    <label class="radio-inline"><input type="radio" value="0" name="disablity" id="disablity" checked>
                        <span class="text-warning">No</span></label>
                </div>
            </div>
        </div>
    </div>
</fieldset>

{{-- Correspondense Address --}}
<fieldset id="correspondense_address">
    <legend>Correspondence Address: </legend>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="correspondence_vill_town" class="col-md-4 control-label text-right margin-label">Vill./Town:
                    <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="correspondence_vill_town" class="form-control present_address" required
                        placeholder="Vill./Town">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="correspondence_po" class="col-md-4 control-label text-right margin-label">PO: <span
                        class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="correspondence_po" class="form-control present_address" required
                        placeholder="PO.">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="correspondence_ps" class="col-md-4 control-label text-right margin-label">PS: <span
                        class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="correspondence_ps" class="form-control present_address" required
                        placeholder="PS">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="correspondence_pin" class="col-md-4 control-label text-right margin-label">PIN: <span
                        class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="number" maxlength="6" minlength="6" name="correspondence_pin"
                        class="form-control present_address" required placeholder="PIN.">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="correspondence_state" class="col-md-4 control-label text-right margin-label">State: <span
                        class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="correspondence_state" class="form-control present_address" required
                        placeholder="State">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="correspondence_district" class="col-md-4 control-label text-right margin-label">District:
                    <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="correspondence_district" class="form-control present_address" required
                        placeholder="District.">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="correspondence_contact" class="col-md-4 control-label text-right margin-label">Contact
                    Number:
                    <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="correspondence_contact" class="form-control present_address" required
                        placeholder="Contact Number" maxlength="10" minlength="10"
                        value="{{auth()->guard("student")->user()->mobile_no}}">
                </div>
            </div>
        </div>
    </div>
</fieldset>

{{-- Permanent Address --}}
<fieldset id="permanent_address">
    <legend>Permanent Address: <span class="pull-right"><label class="checkbox-inline"><input type="checkbox" value="1"
                    name="same_address" id="address_same"> <span class="text-warning"> Same as Correspondence
                    address</span></label></span></legend>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="permanent_vill_town" class="col-md-4 control-label text-right margin-label">Vill./Town:
                    <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="permanent_vill_town" class="form-control permanent_address" required
                        placeholder="Vill./Town">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="permanent_po" class="col-md-4 control-label text-right margin-label">PO: <span
                        class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="permanent_po" class="form-control permanent_address" required
                        placeholder="PO.">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="permanent_ps" class="col-md-4 control-label text-right margin-label">PS: <span
                        class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="permanent_ps" class="form-control permanent_address" required
                        placeholder="PS">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="permanent_pin" class="col-md-4 control-label text-right margin-label">PIN: <span
                        class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="number" name="permanent_pin" maxlength="6" minlength="6"
                        class="form-control permanent_address" required placeholder="PIN.">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="permanent_state" class="col-md-4 control-label text-right margin-label">State: <span
                        class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="permanent_state" class="form-control permanent_address" required
                        placeholder="State">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="permanent_district" class="col-md-4 control-label text-right margin-label">District: <span
                        class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="permanent_district" class="form-control permanent_address" required
                        placeholder="District.">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="permanent_contact" class="col-md-4 control-label text-right margin-label">Contact Number:
                    <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="permanent_contact" class="form-control permanent_address" required
                        placeholder="Contact Number" maxlength="10" minlength="10">
                </div>
            </div>
        </div>
    </div>
</fieldset>
{{-- academic details --}}
<fieldset id="academic_details">
    <legend>Academic Details</legend>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
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
                    <td>----<input type="hidden" name="academic_10_stream" value="NA"></td>
                    <td><input required type="number" name="academic_10_year" class="form-control"
                            placeholder="Year of Passing" maxlength="4" minlength="4"></td>
                    <td><input required type="text" name="academic_10_board" class="form-control"
                            placeholder="Board/Council"></td>
                    <td><input required type="text" name="academic_10_school" class="form-control"
                            placeholder="Name of School"></td>
                    <td><input required type="text" name="academic_10_subject" class="form-control"
                            placeholder="Subjects"></td>
                    <td><input required type="number" name="academic_10_mark" class="form-control"
                            placeholder="Marks Obtained" max="1000" min="0"></td>
                    <td><input required type="number" name="academic_10_percentage" class="form-control"
                            placeholder="Percentage" max="100" min="0"></td>
                </tr>
                <tr>
                    <th>12<sup>th</sup></th>
                    <td><input required type="text" name="academic_12_stream" class="form-control" placeholder="Stream">
                    </td>
                    <td><input required type="number" name="academic_12_year" class="form-control"
                            placeholder="Year of Passing"></td>
                    <td><input required type="text" name="academic_12_board" class="form-control"
                            placeholder="Board/Council"></td>
                    <td><input required type="text" name="academic_12_school" class="form-control"
                            placeholder="Name of School"></td>
                    <td><input required type="text" name="academic_12_subject" class="form-control"
                            placeholder="Subjects"></td>
                    <td><input required type="number" name="academic_12_mark" class="form-control"
                            placeholder="Marks Obtained"></td>
                    <td><input required type="number" name="academic_12_percentage" class="form-control"
                            placeholder="Percentage"></td>
                </tr>
                <tr>
                    <th>Vocational</th>
                    <td><input type="text" name="academic_voc_stream" class="form-control" placeholder="Stream"></td>
                    <td><input type="number" name="academic_voc_year" class="form-control"
                            placeholder="Year of Passing"></td>
                    <td><input type="text" name="academic_voc_board" class="form-control" placeholder="Board/Council">
                    </td>
                    <td><input type="text" name="academic_voc_school" class="form-control" placeholder="Name of School">
                    </td>
                    <td><input type="text" name="academic_voc_subject" class="form-control" placeholder="Subjects"></td>
                    <td><input type="number" name="academic_voc_mark" class="form-control" placeholder="Marks Obtained">
                    </td>
                    <td><input type="number" name="academic_voc_percentage" class="form-control"
                            placeholder="Percentage"></td>
                </tr>
                <tr>
                    <th>ANM</th>
                    <td><input type="text" name="academic_anm_stream" class="form-control" placeholder="Stream"></td>
                    <td><input type="number" name="academic_anm_year" class="form-control"
                            placeholder="Year of Passing"></td>
                    <td><input type="text" name="academic_anm_board" class="form-control" placeholder="Board/Council">
                    </td>
                    <td><input type="text" name="academic_anm_school" class="form-control" placeholder="Name of School">
                    </td>
                    <td><input type="text" name="academic_anm_subject" class="form-control" placeholder="Subjects"></td>
                    <td><input type="number" name="academic_anm_mark" class="form-control" placeholder="Marks Obtained">
                    </td>
                    <td><input type="number" name="academic_anm_percentage" class="form-control"
                            placeholder="Percentage"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="other_qualification" class="col-md-4 control-label text-right margin-label">Any Other
                    Qualification: <span class="text-danger">* (if not available enter NA)</span></label>
                <div class="col-md-7">
                    <input type="text" name="other_qualification" class="form-control" required
                        placeholder="Other Qualification">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="english_mark_obtained" class="col-md-4 control-label text-right margin-label">English mark
                    obtained in 12<sup>th</sup> <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="text" name="english_mark_obtained" max="100" min="40" class="form-control" required
                        placeholder="Marks">
                </div>
            </div>
        </div>
    </div>
</fieldset>
{{-- Certificate --}}
<fieldset id="certificate">
    <div class="col-md-12 col-lg-12">
        <div class="alert alert-warning">
            <strong><i class="fa fa-exclamation-triangle"></i></strong> File Size Maximum Limit 1MB (except passport
            photo and signature)
        </div>
    </div>
    <legend>Certificates</legend>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="passport_photo" class="col-md-4 control-label text-right margin-label">Passport Photo:
                    <span class="text-danger">* max 200px(W) X max 250px(H) and max size 100KB</span></label>
                <div class="col-md-7">
                    <input type="file" name="passport_photo" class="form-control" placeholder="Passport Photo" required accept=".jpg,.jpeg,.png">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="signature" class="col-md-4 control-label text-right margin-label">Signature:
                    <span class="text-danger">* max 200px(W) X max 150px(H) and max size 100KB</span></label>
                <div class="col-md-7">
                    <input type="file" name="signature" class="form-control" required placeholder="Signature" accept=".jpg,.jpeg,.png">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="caste_certificate" class="col-md-4 control-label text-right margin-label">Caste Certificate:
                    <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="file" name="caste_certificate" class="form-control" placeholder="Caste Certificate"  accept=".jpg,.jpeg,.png,.pdf">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="prc_certificate" class="col-md-4 control-label text-right margin-label">PRC Certificate:
                    <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="file" name="prc_certificate" class="form-control" required
                        placeholder="PRC Certificate"  accept=".jpg,.jpeg,.png,.pdf">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="age_proof_certificate" class="col-md-4 control-label text-right margin-label">Age Proof
                    Certificate:
                    <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="file" name="age_proof_certificate" class="form-control" required
                        placeholder="Age Proof Certificate" accept=".jpg,.jpeg,.png,.pdf">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="disablity_certificate" class="col-md-4 control-label text-right margin-label">Disability
                    Certificate:
                    <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="file" name="disablity_certificate" class="form-control"
                        placeholder="Disability Certificate" accept=".jpg,.jpeg,.png,.pdf">
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="12_admit_card" class="col-md-4 control-label text-right margin-label">10+2 / equivalent
                    examination qualifying <strong> card</strong>
                    <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="file" name="12_admit_card" class="form-control" required placeholder="Admit Card"  accept=".jpg,.jpeg,.png,.pdf">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="12_marksheet" class="col-md-4 control-label text-right margin-label">10+2 / equivalent
                    examination qualifying <strong>marksheet</strong>
                    <span class="text-danger">*</span></label>
                <div class="col-md-7">
                    <input type="file" name="12_marksheet" class="form-control" required
                        placeholder="Disability Certificate" accept=".jpg,.jpeg,.png,.pdf">
                </div>
            </div>
        </div>
    </div>
</fieldset>
<div class="row">
    <div class="col-sm-12">
        <label class="checkbox-inline"><input type="checkbox" name="accept" id="accept" value="1"
                required>Accept</label>
        <p>I hereby, declare that the above statements provided by me are true and complete to the best of my knowledge.
            If any
            information is found to be incorrect or untrue at any stage, I shall be liable for the same and have no
            claim
            against the cancellation and /or taking any other legal action as deemed fit by the authority.</p>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 text-center">
        <button type="submit" class="btn btn-md btn-success disabled" id="submit_btn">Save/Submit</button>
    </div>
</div>
<br>
<br>
<br>
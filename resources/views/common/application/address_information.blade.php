<div class="step" class="address">
    {{-- Correspondense Address --}}
    <fieldset id="correspondense_address">
        <legend>Correspondence Address: <span class="pull-right text-danger">(Step 2)</span></legend>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="correspondence_vill_town"
                        class="col-md-4 control-label text-right margin-label">Vill./Town:
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="text" name="correspondence_vill_town" class="form-control present_address" required
                            placeholder="Vill./Town"
                            @if (isset($application))
                                value="{{old("correspondence_vill_town", $application->correspondence_village_town)}}"
                            @else
                                value="{{old("correspondence_vill_town")}}"
                            @endif
                            >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="correspondence_po" class="col-md-4 control-label text-right margin-label">PO: <span
                            class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="text" name="correspondence_po" class="form-control present_address" required
                            placeholder="PO."
                            @if (isset($application))
                                value="{{old("correspondence_po", $application->correspondence_po)}}"
                            @else
                                value="{{old("correspondence_po")}}"
                            @endif
                        >
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
                            placeholder="PS"
                            @if (isset($application))
                                value="{{old("correspondence_ps", $application->correspondence_ps)}}"
                            @else
                                value="{{old("correspondence_ps")}}"
                            @endif    
                        >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="correspondence_pin" class="col-md-4 control-label text-right margin-label">PIN: <span
                            class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="number" maxlength="6" minlength="6" name="correspondence_pin"
                            class="form-control present_address" required placeholder="PIN." min="0"
                            @if (isset($application))
                                value="{{old("correspondence_pin", $application->correspondence_pin)}}"
                            @else
                                value="{{old("correspondence_pin")}}"
                            @endif
                        >
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="correspondence_state" class="col-md-4 control-label text-right margin-label">State:
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="text" name="correspondence_state" class="form-control present_address" required
                            placeholder="State"
                            @if (isset($application))
                                value="{{old("correspondence_state", $application->correspondence_state)}}"
                            @else
                                value="{{old("correspondence_state")}}"
                            @endif    
                        >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="correspondence_district"
                        class="col-md-4 control-label text-right margin-label">District:
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="text" name="correspondence_district" class="form-control present_address" required
                            placeholder="District."
                            @if (isset($application))
                                value="{{old("correspondence_district", $application->correspondence_district)}}"
                            @else
                                value="{{old("correspondence_district")}}"
                            @endif      
                        >
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
                        <input type="number" name="correspondence_contact" class="form-control present_address" required
                            placeholder="Contact Number" maxlength="10" minlength="10"
                            @if (isset($application) && $application->correspondence_contact_number!=null) 
                                value="{{old("correspondence_contact", $application->correspondence_contact_number)}}"
                            @else
                                value="{{old("correspondence_contact", auth("student")->user()->mobile_no)}}"
                            @endif  
                        >
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    @php
    $same_address = "";
    $same_address_readonly = "";
        if (isset($application) && $application->same_address){
            $same_address ="checked";
            $same_address_readonly ="readonly";
        }
    @endphp
    {{-- Permanent Address --}}
    <fieldset id="permanent_address">
        <legend>Permanent Address: <span class="pull-right"><label class="checkbox-inline"><input type="checkbox"
                        value="1" name="same_address" id="address_same"
                        {{$same_address}}
                        > <span class="text-warning"> Same as
                        Correspondence
                        address</span></label></span></legend>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="permanent_vill_town" class="col-md-4 control-label text-right margin-label">Vill./Town:
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="text" name="permanent_vill_town" {{$same_address_readonly}} class="form-control permanent_address" required
                            placeholder="Vill./Town"
                            @if (isset($application))
                                value="{{old("permanent_vill_town", $application->permanent_village_town)}}"
                            @else
                                value="{{old("permanent_vill_town")}}"
                            @endif
                        >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="permanent_po" class="col-md-4 control-label text-right margin-label">PO: <span
                            class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="text" name="permanent_po" {{$same_address_readonly}} class="form-control permanent_address" required
                            placeholder="PO."
                            @if (isset($application))
                                value="{{old("permanent_po", $application->permanent_po)}}"
                            @else
                                value="{{old("permanent_po")}}"
                            @endif
                        >
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
                        <input type="text" name="permanent_ps" {{$same_address_readonly}} class="form-control permanent_address" required
                            placeholder="PS"
                            @if (isset($application))
                                value="{{old("permanent_ps", $application->permanent_ps)}}"
                            @else
                                value="{{old("permanent_ps")}}"
                            @endif
                        >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="permanent_pin" class="col-md-4 control-label text-right margin-label">PIN: <span
                            class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="number" name="permanent_pin" maxlength="6" minlength="6"
                            class="form-control permanent_address" {{$same_address_readonly}} required placeholder="PIN." min="0"
                            @if (isset($application))
                                value="{{old("permanent_pin", $application->permanent_pin)}}"
                            @else
                                value="{{old("permanent_pin")}}"
                            @endif
                        >
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
                        <input type="text" name="permanent_state" {{$same_address_readonly}} class="form-control permanent_address" required
                            placeholder="State"
                            @if (isset($application))
                                value="{{old("permanent_state", $application->permanent_state)}}"
                            @else
                                value="{{old("permanent_state")}}"
                            @endif
                        >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="permanent_district" class="col-md-4 control-label text-right margin-label">District:
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="text" name="permanent_district" {{$same_address_readonly}} class="form-control permanent_address" required
                            placeholder="District."
                            @if (isset($application))
                                value="{{old("permanent_district", $application->permanent_district)}}"
                            @else
                                value="{{old("permanent_district")}}"
                            @endif
                        >
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="permanent_contact" class="col-md-4 control-label text-right margin-label">Contact
                        Number:
                        <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="number" name="permanent_contact" {{$same_address_readonly}} class="form-control permanent_address" required
                            placeholder="Contact Number" maxlength="10" minlength="10"
                            @if (isset($application))
                                value="{{old("permanent_contact", $application->permanent_contact_number)}}"
                            @else
                                value="{{old("permanent_contact")}}"
                            @endif
                        >
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="row">
        <div class="col-xs-12 text-right">
            {{-- <button type="button" class="btn btn-primary previous" id="previous">Previous</button> --}}
            <button type="submit" class="btn btn-success">Submit & Update <i class="fa fa-save"></i></button>
        </div>
    </div>
</div>
<div class="filter dont-print">
    <div class="row">
        <div class="col-sm-3">
            <label for="application_id" class="label-control">Application No:</label>
            <input type="number" name="application_id" id="application_id" class="form-control"
                placeholder="Application No" value="{{request()->get("application_id")}}" min="0">
        </div>
        <div class="col-sm-3">
            <label for="registration_no" class="label-control">Registration No:</label>
            <input type="number" name="registration_no" id="registration_no" class="form-control"
                placeholder="Registration No" value="{{request()->get("registration_no")}}" min="0">
        </div>

        <div class="col-sm-3">
            <label for="applicant_name" class="label-control">Applicant Name:</label>
            <input type="text" name="applicant_name" id="applicant_name" class="form-control"
                placeholder="Applicant Name" value="{{request()->get("applicant_name")}}">
        </div>
                
        <div class="col-sm-3">
            <label for="activity" class="label-control">Activity:</label>
            <input type="text" name="activity" id="activity" class="form-control"
                placeholder="Activity" value="{{request()->get("activity")}}">
        </div>
    </div>
    <br>
    <div class="row">        

        <div class="col-sm-3">
            <label for="date" class="label-control">Date From:</label>
            <input type="text" name="date_from" id="date_from" class="form-control zebra"
                placeholder="Date from" value="{{request()->get("date_from")}}">
        </div>
        <div class="col-sm-3">
            <label for="date" class="label-control">Date To:</label>
            <input type="text" name="date_to" id="date_to" class="form-control zebra"
                placeholder="Date To" value="{{request()->get("date_to")}}">
        </div>        
        <div class="col-sm-3">
            <label for="user_type" class="label-control">User Type:</label>
            <select name="user_type" id="user_type" class="form-control">
                <option value="">--SELECT--</option>
                <option value="admin" {{(request()->get("user_type") == "admin" ? "selected" : "")}}>Admin</option>
                <option value="student" {{(request()->get("user_type") == "student" ? "selected" : "")}}>Student</option>
            </select>
        </div>
        <div class="col-sm-3">
            <label for="limit" class="label-control">Limit:</label>
            <input type="number" name="limit" id="limit" class="form-control"
                placeholder="eg: 100" value="{{request()->get("limit")}}" min="1">
        </div>
        <div class="col-sm-3">
            <label for="ip" class="label-control">ip:</label>
            <input type="ip" name="ip" id="ip" class="form-control" data-inputmask="'alias': 'ip'"
                placeholder="127.0.0.1" value="{{request()->get("ip")}}">
        </div>
        <div class="col-sm-3">
            <label for="submit" class="label-control" style="visibility: hidden;">Search</label><br>
            <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
            <button type="reset" class="btn btn-danger"><i class="fa fa-remove"></i> Reset</button>
        </div>
    </div>
</div>
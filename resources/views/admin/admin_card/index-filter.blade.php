<div class="filter dont-print">
    <div class="row">
        <div class="col-sm-3">
            <label for="application_id" class="label-control">Application No:</label>
            <input type="number" name="application_id" id="application_id" class="form-control"
                placeholder="Application No" value="{{request()->get("application_id")}}">
        </div>
        <div class="col-sm-3">
            <label for="registration_no" class="label-control">Registration No:</label>
            <input type="number" name="registration_no" id="registration_no" class="form-control"
                placeholder="Registration No" value="{{request()->get("registration_no")}}">
        </div>

        <div class="col-sm-3">
            <label for="application_id" class="label-control">Caste:</label>
            <select name="caste" id="caste" class="form-control">
                <option value="" selected>All</option>
                @foreach ($castes as $index => $caste)
                <option value="{{$caste->id}}" {{request()->get("caste") == $caste->id ? "selected" : ""}}>
                    {{$caste->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <label for="applicant_name" class="label-control">Applicant Name:</label>
            <input type="text" name="applicant_name" id="applicant_name" class="form-control"
                placeholder="Applicant Name" value="{{request()->get("applicant_name")}}">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-3">
            <label for="limit" class="label-control">Limit:</label>
            <input type="number" name="limit" id="limit" class="form-control"
                placeholder="eg: 100" value="{{request()->get("limit")}}">
        </div>
        <div class="col-sm-3">
            <label for="limit" class="label-control">Exam Center:</label>
            <select name="exam_center" id="exam_center" class="form-control">
                <option value="">--SELECT--</option>
                @foreach ($exam_centers as $exam_center)
                    <option {{request()->get("exam_center") == $exam_center->id ? "selected" : ""}} value="{{$exam_center->id}}">{{$exam_center->center_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <label for="limit" class="label-control">Session:</label>           
            <select name="session" id="session" class="form-control">
                <option value="">--Select--</option>
                @foreach ($sessioon as $sess)
                <option {{request()->get("session") == $sess->id ? "selected" : ""}} value="{{$sess->id}}">{{$sess->name}}</option>
                @endforeach
            </select>          
        </div>
        <div class="col-sm-3">
            <label for="submit" class="label-control" style="visibility: hidden;">Search</label><br>
            <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
            <button type="reset" class="btn btn-danger"><i class="fa fa-remove"></i> Reset</button>
        </div>
    </div>
</div>
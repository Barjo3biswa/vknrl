<div class="filter dont-print">
    {{-- <div class="row">
        <div class="col-sm-3">
            <label for="application_id" class="label-control">Session:</label>
        </div>
    </div>
    <br/> --}}

    <div class="row">
        <div class="col-sm-3">
            <label for="application_id" class="label-control">Application No:</label>
            <input type="text" name="application_id" id="application_id" class="form-control input-sm"
                placeholder="Application No" value="{{request()->get("application_id")}}">
        </div>
        <div class="col-sm-3">
            <label for="application_id" class="label-control">Application Status:</label>
            {{-- <input type="text" name="application_id" id="application_id" class="form-control input-sm" placeholder="Application No"> --}}
            <select name="status" id="status" class="form-control input-sm">
                <option value="all">All</option>
                {{-- <option value="payment_pending">Payment Pending</option> --}}
                <option value="payment_done" {{request()->get("status") == "payment_done" ? "selected" : ""}}>Payment Done</option>
                <option value="accepted" {{request()->get("status") == "accepted" ? "selected" : ""}}>Accepted</option>
                <option value="rejected" {{request()->get("status") == "rejected" ? "selected" : ""}}>Rejected</option>
                <option value="on_hold" {{request()->get("status") == "on_hold" ? "selected" : ""}}>On Hold</option>
                <option value="qualified" {{request()->get("status") == "qualified" ? "selected" : ""}}>Qualified</option>
            </select>
        </div>
        <div class="col-sm-3">
            <label for="application_id" class="label-control">Caste:</label>
            {{-- <input type="text" name="application_id" id="application_id" class="form-control input-sm" placeholder="Application No"> --}}
            <select name="caste" id="caste" class="form-control input-sm">
                <option value="" selected>All</option>
                @foreach ($castes as $index => $caste)
                    <option value="{{$caste->id}}" {{request()->get("caste") == $caste->id ? "selected" : ""}}>{{$caste->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <label for="application_id" class="label-control">ANM/LHV:</label>
            {{-- <input type="text" name="application_id" id="application_id" class="form-control input-sm" placeholder="Application No"> --}}
            <select name="anm_or_lhv" id="anm_or_lhv" class="form-control input-sm">
                <option value="" {{request()->get("anm_or_lhv") == "" ? "selected" : ""}}>All</option>
                <option value="1" {{(int)request()->get("anm_or_lhv") === 1 ? "selected" : ""}}>Yes</option>
                <option value="0" {{((int)request()->get("anm_or_lhv") === 0 && request()->get("anm_or_lhv") != "") ? "selected" : ""}}>No</option>
            </select>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-sm-3">
            <label for="aplicant_name" class="label-control">Applicant Name:</label>
            <input type="text" name="aplicant_name" id="aplicant_name" class="form-control input-sm"
            placeholder="Applicant Name" value="{{request()->get("aplicant_name")}}">
        </div>
        
        <div class="col-sm-3">
            <label for="payment_date" class="label-control">Payment Date From:</label>
            <input type="text" name="payment_date_from" id="payment_date_from" class="form-control input-sm zebra"
                placeholder="Application No" value="{{request()->get("payment_date_from")}}">
        </div>
        <div class="col-sm-3">
            <label for="payment_date" class="label-control">Payment Date To:</label>
            <input type="text" name="payment_date_to" id="payment_date_to" class="form-control input-sm zebra"
                placeholder="Application No" value="{{request()->get("payment_date_to")}}">
        </div>
        {{-- <div class="col-sm-3">
            <label for="order" class="label-control">Order By:</label>
            <select name="order" id="order" class="form-control input-sm">
                <option value="application_no" {{request()->get("order") === "application_no" ? "selected" : ""}}>Application No</option>
                <option value="registration_no" {{request()->get("order") === "registration_no" ? "selected" : ""}}>Registration No</option>
                <option value="applicant_name" {{request()->get("order") === "applicant_name" ? "selected" : ""}}>Applicant Name</option>
            </select>
        </div>
        <div class="col-sm-3">
            <label for="order" class="label-control">ASC/DESC:</label>
            <select name="order" id="order" class="form-control input-sm">
                <option value="ASC" {{request()->get("order") === "ASC" ? "selected" : ""}}>ASC</option>
                <option value="DESC" {{request()->get("order") === "DESC" ? "selected" : ""}}>DESC</option>
            </select>
        </div> --}}
        <div class="col-sm-3">
            <label for="submit" class="label-control" style="visibility: hidden;">Search</label><br>
            <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-search"></i> Filter</button>
            <button type="reset" class="btn btn-danger btn-sm"><i class="fa fa-remove"></i> Reset</button>
        </div>
        {{-- <div class="col-sm-3">
            <label for="reset" class="label-control" style="visibility: hidden;">Reset</label><br>
        </div> --}}
    </div>
</div>
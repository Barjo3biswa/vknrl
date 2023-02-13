<div class="filter dont-print">
    <div class="row">
        <div class="col-sm-3">
            <label for="applicant_name_starting" class="label-control">Applicant Name Starting with:</label>
            <input type="text" name="applicant_name_starting" id="applicant_name_starting" class="form-control"
                placeholder="eg. A" value="{{request()->get("applicant_name_starting")}}">
        </div>
        <div class="col-sm-3">
            <label for="application_id" class="label-control">Application No(From):</label>
            <input type="number" name="application_id_from" id="application_id_from" class="form-control"
                placeholder="Application No (From)" value="{{request()->get("application_id_from")}}">
        </div>
        <div class="col-sm-3">
            <label for="application_id" class="label-control">Application No(To):</label>
            <input type="number" name="application_id_to" id="application_id_to" class="form-control"
                placeholder="Application No (To)" value="{{request()->get("application_id_to")}}">
        </div>
        <div class="col-sm-3">
            <label for="application_id" class="label-control">Admit Card Status:</label>
            {{-- <input type="text" name="application_id" id="application_id" class="form-control" placeholder="Application No"> --}}
            <select name="status" id="status" class="form-control">
                <option value="all">All</option>
                <option value="not_generated" {{request()->get("status") == "not_generated" ? "selected" : ""}}>Not
                    Generated</option>
                <option value="draft" {{request()->get("status") == "draft" ? "selected" : ""}}>Draft</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <label for="limit" class="label-control">Limit: </label>
            <input type="number" name="limit" id="limit" class="form-control"
                placeholder="eg: 1000" value="{{request()->get("limit")}}">    
        </div>
    </div>
    <br>
    <div class="row">

        <div class="col-sm-3">
            <label for="submit" class="label-control" style="visibility: hidden;">Search</label><br>
            <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Filter</button>
            <button type="reset" class="btn btn-danger"><i class="fa fa-remove"></i> Reset</button>
        </div>
    </div>
</div>
{{ csrf_field() }}
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="center_name" class="col-md-3 control-label text-right margin-label">
                Center Name: <span class="text-danger">*</span></label>
            <div class="col-md-8">
                <input type="text" name="center_name" class="form-control" required placeholder="Center Name"
                    @isset($exam_center) value="{{old("center_name",$exam_center->center_name)}}" @else
                    value="{{old("center_name")}}" @endisset>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="center_code" class="col-md-3 control-label text-right margin-label">
                Center Code: </label>
            <div class="col-md-8">
                <input type="text" name="center_code" class="form-control" placeholder="Center Code"
                    @isset($exam_center) value="{{old("center_code",$exam_center->center_code)}}" @else
                    value="{{old("center_code")}}" @endisset>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="address" class="col-md-3 control-label text-right margin-label">
                Center Address: <span class="text-danger">*</span></label>
            <div class="col-md-8">
                {{-- <input type="text" name="address" class="form-control" required
                                                    placeholder="Center Name" @isset($exam_center)
                                                    value="{{old("address",$exam_center->address)}}" @else
                value="{{old("address")}}"
                @endisset> --}}
                <textarea name="address" id="address" cols="30" rows="2" class="form-control" required
                    placeholder="Center Address">@isset($exam_center){{old("address",$exam_center->address)}}@else{{old("address")}}@endisset</textarea>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="center_name" class="col-md-3 control-label text-right margin-label">
                City: <span class="text-danger">*</span></label>
            <div class="col-md-8">
                <input type="text" name="city" class="form-control" required placeholder="City" @isset($exam_center)
                    value="{{old("city",$exam_center->city)}}" @else value="{{old("city")}}" @endisset>
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="state" class="col-md-3 control-label text-right margin-label">
                State: <span class="text-danger">*</span></label>
            <div class="col-md-8">
                <input type="text" name="state" class="form-control" required placeholder="State" @isset($exam_center)
                    value="{{old("state",$exam_center->state)}}" @else value="{{old("state")}}" @endisset>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="center_name" class="col-md-3 control-label text-right margin-label">
                Pin: <span class="text-danger">*</span></label>
            <div class="col-md-8">
                <input type="number" size="6" minlength="6" maxlength="6" name="pin" min="0" class="form-control"
                    required placeholder="Pin" @isset($exam_center) value="{{old("pin",$exam_center->pin)}}" @else
                    value="{{old("pin")}}" @endisset>
            </div>
        </div>
    </div>
</div>
<br>
<br>
<div class="row">
    <div class="col-sm-12 text-center">
        <button class="btn btn-md btn-success"><i class="fa fa-save"></i> Save</button>
    </div>
</div>
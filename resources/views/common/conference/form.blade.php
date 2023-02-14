<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-1"></div>
            <div class="col-md-3">
                <label for="name" class="col-md-8">First Name</label>
                <input type="text" class="col-md-8 form-control" name="f_name"
                    value="{{ old('f_name') }}" required>
            </div>
            <div class="col-md-3">
                <label for="name" class="col-md-8">Middle Name</label>
                <input type="text" class="col-md-8 form-control has-error" name="m_name"
                    value="{{ old('m_name') }}">
            </div>
            <div class="col-md-3">
                <label for="name" class="col-md-8">Last Name</label>
                <input type="text" class="col-md-8 form-control" name="l_name"
                    value="{{ old('l_name') }}" required>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-2">Email Id</div>
            <div class="col-md-7">
                <input type="email" class="form-control" name="email"
                    value="{{ old('email') }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-2">Phone Number</div>
            <div class="col-md-7">
                <input type="number"
                    class="form-control {{ $errors->has('phone_no') ? 'is-invalid' : '' }}"
                    name="phone_no" value="{{ old('phone_no') }}" required>
                @if ($errors->has('phone_no'))
                    <span class="text-danger">
                        {{ $errors->first('phone_no') }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <div class="col-md-2">Name of the Institution:</div>
            <div class="col-md-7">
                <input type="text" class="form-control" name="institution_name"
                    value="{{ old('institution_name') }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md-2">Designation:</div>
            <div class="col-md-7">
                <input type="text" class="form-control" name="designation"
                    value="{{ old('designation') }}">
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-1">Address:</div>
            <div class="col-md-6">
                <input type="text" class="form-control" name="address"
                    value="{{ old('address') }}" required>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">Are you participating in Scientific Paper Presentation ( Please
                tick):
            </div>
            <div class="col-md-1">
                <input type="radio" name="scientific_paper" value="1"
                    {{ old('scientific_paper') == 1 ? 'checked' : '' }}>
                <label for="html">Yes</label><br>
            </div>
            <div class="col-md-1">
                <input type="radio" name="scientific_paper" value="2"
                    {{ old('scientific_paper') == 2 ? 'checked' : null }}>
                <label for="css">No</label><br>
            </div>
            <div class="col-md-4">
                @if ($errors->has('scientific_paper'))
                    <span class="text-danger">
                        {{ $errors->first('scientific_paper') }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>



<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">Are you participating in Poster Presentation:</div>
            <div class="col-md-1">
                <input type="radio" name="poster_presentation" value="1"
                    {{ old('poster_presentation') == 1 ? 'checked' : '' }}>
                <label for="html">Yes</label><br>
            </div>
            <div class="col-md-1">
                <input type="radio" name="poster_presentation" value="2"
                    {{ old('poster_presentation') == 2 ? 'checked' : '' }}>
                <label for="css">No</label><br>
            </div>
            <div class="col-md-4">
                @if ($errors->has('poster_presentation'))
                    <span class="text-danger">
                        {{ $errors->first('poster_presentation') }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-3">Do you need Accommodation:</div>
            <div class="col-md-3">*9 th March 2023:</div>
            <div class="col-md-1">
                <input type="radio" name="st_day" value="1"
                    {{ old('st_day') == 1 ? 'checked' : '' }}>
                <label for="html">Yes</label><br>
            </div>
            <div class="col-md-1">
                <input type="radio" name="st_day" value="2"
                    {{ old('st_day') == 2 ? 'checked' : '' }}>
                <label for="css">No</label><br>
            </div>
            <div class="col-md-4">
                @if ($errors->has('st_day'))
                    <span class="text-danger">
                        {{ $errors->first('st_day') }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-3"></div>
            <div class="col-md-3">*10 th March 2023:</div>
            <div class="col-md-1">
                <input type="radio" name="nd_day" value="1"
                    {{ old('nd_day') == 1 ? 'checked' : '' }}>
                <label for="html">Yes</label><br>
            </div>
            <div class="col-md-1">
                <input type="radio" name="nd_day" value="2"
                    {{ old('nd_day') == 2 ? 'checked' : '' }}>
                <label for="css">No</label><br>
            </div>
            <div class="col-md-4">
                @if ($errors->has('nd_day'))
                    <span class="text-danger">
                        {{ $errors->first('nd_day') }}
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-7">
            <input type="submit" class="btn btn-primary" value="Submit & Proceed to Payment"
                style="float: right">
        </div>
    </div>
</div>
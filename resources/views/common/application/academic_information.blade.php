@php
    $hs_min_percentage  = 40;
    $anm_min_percentage  = 0;
    $disable_anm  = "";
    if(isset($application)){
        if($application->anm_or_lhv){
            $hs_min_percentage = 30;
            $anm_min_percentage = 40;
        }elseif(strtolower(trim($application->caste->name)) == "sc" || strtolower(trim($application->caste->name)) == "st"){
            $hs_min_percentage = 35;
        }
        if(!$application->anm_or_lhv){
            $disable_anm = "disabled";
        }
    }
@endphp
<div class="step" class="academic_details">
    {{-- academic details --}}
    <fieldset id="academic_details">
        <legend>Academic Details: <span class="pull-right text-danger">(Step 3)</span></legend>
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
                                placeholder="Year of Passing" maxlength="4" minlength="4" min="0"
                                @if (isset($application))
                                    value="{{old("academic_10_year", $application->academic_10_year)}}"
                                @else
                                    value="{{old("academic_10_year")}}"
                                @endif
                            ></td>
                        <td><input required type="text" name="academic_10_board" class="form-control"
                                placeholder="Board/Council"
                                @if (isset($application))
                                    value="{{old("academic_10_board", $application->academic_10_board)}}"
                                @else
                                    value="{{old("academic_10_board")}}"
                                @endif    
                            ></td>
                        <td><input required type="text" name="academic_10_school" class="form-control"
                                placeholder="Name of School"
                                @if (isset($application))
                                    value="{{old("academic_10_school", $application->academic_10_school)}}"
                                @else
                                    value="{{old("academic_10_school")}}"
                                @endif    
                            ></td>
                        <td><textarea cols="30" rows="2" required name="academic_10_subject" class="form-control"
                            placeholder="Subjects">@if (isset($application)){{old("academic_10_subject", $application->academic_10_subject)}}@else{{old("academic_10_subject")}}@endif</textarea>
                        </td>
                        <td><input required type="number" name="academic_10_mark" class="form-control"
                                placeholder="Marks Obtained" max="1000" min="0"
                                @if (isset($application))
                                    value="{{old("academic_10_mark", $application->academic_10_mark)}}"
                                @else
                                    value="{{old("academic_10_mark")}}"
                                @endif 
                            ></td>
                        <td><input required type="number" name="academic_10_percentage" class="form-control"
                                placeholder="Percentage" max="100" min="30"
                                @if (isset($application))
                                    value="{{old("academic_10_percentage", $application->academic_10_percentage)}}"
                                @else
                                    value="{{old("academic_10_percentage")}}"
                                @endif
                            ></td>
                    </tr>
                    <tr>
                        <th>12<sup>th</sup></th>
                        <td><input required type="text" name="academic_12_stream" class="form-control"
                                placeholder="Stream"
                                @if (isset($application))
                                    value="{{old("academic_12_stream", $application->academic_12_stream)}}"
                                @else
                                    value="{{old("academic_12_stream")}}"
                                @endif
                            >
                        </td>
                        <td><input required type="number" name="academic_12_year" class="form-control"
                                placeholder="Year of Passing" min="0" minlength="4"  maxlength="4"
                                @if (isset($application))
                                    value="{{old("academic_12_year", $application->academic_12_year)}}"
                                @else
                                    value="{{old("academic_12_year")}}"
                                @endif
                            ></td>
                        <td><input required type="text" name="academic_12_board" class="form-control"
                                placeholder="Board/Council"
                                @if (isset($application))
                                    value="{{old("academic_12_board", $application->academic_12_board)}}"
                                @else
                                    value="{{old("academic_12_board")}}"
                                @endif
                            ></td>
                        <td><input required type="text" name="academic_12_school" class="form-control"
                                placeholder="Name of School"
                                @if (isset($application))
                                    value="{{old("academic_12_school", $application->academic_12_school)}}"
                                @else
                                    value="{{old("academic_12_school")}}"
                                @endif
                            ></td>
                        <td>{{-- <input required type="text" name="academic_12_subject" class="form-control"
                                placeholder="Subjects"
                                @if (isset($application))
                                    value="{{old("academic_12_subject", $application->academic_12_subject)}}"
                                @else
                                    value="{{old("academic_12_subject")}}"
                                @endif
                            > --}}
                            <textarea cols="30" rows="2" required name="academic_12_subject" class="form-control"
                            placeholder="Subjects">@if (isset($application)){{old("academic_12_subject", $application->academic_12_subject)}}@else{{old("academic_12_subject")}}@endif</textarea>
                        </td>
                        <td><input required type="number" name="academic_12_mark" class="form-control"
                                placeholder="Marks Obtained" min="0"
                                @if (isset($application))
                                    value="{{old("academic_12_mark", $application->academic_12_mark)}}"
                                @else
                                    value="{{old("academic_12_mark")}}"
                                @endif
                            ></td>
                        <td><input required type="number" name="academic_12_percentage" class="form-control"
                                placeholder="Percentage" max="100" min="{{$hs_min_percentage}}"
                                @if (isset($application))
                                    value="{{old("academic_12_percentage", $application->academic_12_percentage)}}"
                                @else
                                    value="{{old("academic_12_percentage")}}"
                                @endif
                            ></td>
                    </tr>
                    <tr id="vocational_academic_fields">
                        <th>Vocational</th>
                        <td><input type="text" name="academic_voc_stream" class="form-control" placeholder="Stream"
                                @if (isset($application))
                                    value="{{old("academic_voc_stream", $application->academic_voc_stream)}}"
                                @else
                                    value="{{old("academic_voc_stream")}}"
                                @endif
                            >
                        </td>
                        <td><input type="number" name="academic_voc_year" class="form-control"
                                placeholder="Year of Passing" min="0" minlength="4"  maxlength="4"
                                    @if (isset($application))
                                        value="{{old("academic_voc_year", $application->academic_voc_year)}}"
                                    @else
                                        value="{{old("academic_voc_year")}}"
                                    @endif
                                ></td>
                        <td><input type="text" name="academic_voc_board" class="form-control"
                                placeholder="Board/Council"
                                
                                    @if (isset($application))
                                        value="{{old("academic_voc_board", $application->academic_voc_board)}}"
                                    @else
                                        value="{{old("academic_voc_board")}}"
                                    @endif
                                >
                        </td>
                        <td><input type="text" name="academic_voc_school" class="form-control"
                                placeholder="Name of School"
                                @if (isset($application))
                                    value="{{old("academic_voc_school", $application->academic_voc_school)}}"
                                @else
                                    value="{{old("academic_voc_school")}}"
                                @endif
                            >
                        </td>
                        <td>{{-- <input type="text" name="academic_voc_subject" class="form-control" placeholder="Subjects"
                                @if (isset($application))
                                    value="{{old("academic_voc_subject", $application->academic_voc_subject)}}"
                                @else
                                    value="{{old("academic_voc_subject")}}"
                                @endif
                            > --}}
                            <textarea cols="30" rows="2" name="academic_voc_subject" class="form-control"
                            placeholder="Subjects">@if (isset($application)){{old("academic_voc_subject", $application->academic_voc_subject)}}@else{{old("academic_voc_subject")}}@endif</textarea>
                        </td>
                        <td><input type="number" name="academic_voc_mark" class="form-control"
                                placeholder="Marks Obtained" min="0"
                                    @if (isset($application))
                                        value="{{old("academic_voc_mark", $application->academic_voc_mark)}}"
                                    @else
                                        value="{{old("academic_voc_mark")}}"
                                    @endif
                                >
                        </td>
                        <td><input type="number" name="academic_voc_percentage" class="form-control"
                                placeholder="Percentage" max="100" min="30"
                                @if (isset($application))
                                    value="{{old("academic_voc_percentage", $application->academic_voc_percentage)}}"
                                @else
                                    value="{{old("academic_voc_percentage")}}"
                                @endif
                            ></td>
                    </tr>
                    @php
                        $anm_required = "";
                        if($application->anm_or_lhv){
                            $anm_required = "required";
                        }
                    @endphp
                    <tr id="anm_academic_fields">
                        <th>ANM</th>
                        <td><input type="text" name="academic_anm_stream" class="form-control" placeholder="Stream" {{$anm_required}} {{$disable_anm}}
                                @if (isset($application))
                                    value="{{old("academic_anm_stream", $application->academic_anm_stream)}}"
                                @else
                                    value="{{old("academic_anm_stream")}}"
                                @endif
                            >
                        </td>
                        <td><input type="number" name="academic_anm_year" class="form-control" {{$anm_required}} {{$disable_anm}}
                                placeholder="Year of Passing"  minlength="4"  maxlength="4" min="0"
                                @if (isset($application))
                                    value="{{old("academic_anm_year", $application->academic_anm_year)}}"
                                @else
                                    value="{{old("academic_anm_year")}}"
                                @endif
                            ></td>
                        <td><input type="text" name="academic_anm_board" class="form-control" {{$anm_required}} {{$disable_anm}}
                                placeholder="Board/Council"
                                @if (isset($application))
                                    value="{{old("academic_anm_board", $application->academic_anm_board)}}"
                                @else
                                    value="{{old("academic_anm_board")}}"
                                @endif
                            >
                        </td>
                        <td><input type="text" name="academic_anm_school" class="form-control" {{$anm_required}} {{$disable_anm}}
                                placeholder="Name of School"
                                @if (isset($application))
                                    value="{{old("academic_anm_school", $application->academic_anm_school)}}"
                                @else
                                    value="{{old("academic_anm_school")}}"
                                @endif
                            >
                        </td>
                        <td>{{-- <input type="text" name="academic_anm_subject" class="form-control" placeholder="Subjects"
                                @if (isset($application))
                                    value="{{old("academic_anm_subject", $application->academic_anm_subject)}}"
                                @else
                                    value="{{old("academic_anm_subject")}}"
                                @endif
                            > --}}
                            <textarea cols="30" rows="2" name="academic_anm_subject" class="form-control" {{$anm_required}} {{$disable_anm}}
                            placeholder="Subjects">@if (isset($application)){{old("academic_anm_subject", $application->academic_anm_subject)}}@else{{old("academic_anm_subject")}}@endif</textarea>
                        </td>
                        <td><input type="number" name="academic_anm_mark" class="form-control" {{$anm_required}} {{$disable_anm}}
                                placeholder="Marks Obtained" min="0"
                                
                                @if (isset($application))
                                    value="{{old("academic_anm_mark", $application->academic_anm_mark)}}"
                                @else
                                    value="{{old("academic_anm_mark")}}"
                                @endif
                            >
                        </td>
                        <td><input type="number" name="academic_anm_percentage" class="form-control" {{$anm_required}} {{$disable_anm}}
                                placeholder="Percentage"  max="100" min="{{$anm_min_percentage}}"
                                @if (isset($application))
                                    value="{{old("academic_anm_percentage", $application->academic_anm_percentage)}}"
                                @else
                                    value="{{old("academic_anm_percentage")}}"
                                @endif    
                            ></td>
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
                            placeholder="Other Qualification"
                            @if (isset($application))
                                value="{{old("other_qualification", $application->other_qualification)}}"
                            @else
                                value="{{old("other_qualification")}}"
                            @endif 
                        >
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="english_mark_obtained" class="col-md-4 control-label text-right margin-label">English
                        mark
                        obtained in 12<sup>th</sup> <span class="text-danger">*</span></label>
                    <div class="col-md-7">
                        <input type="number" name="english_mark_obtained" max="100" min="40" class="form-control" required
                            placeholder="Marks" data-error="Mininum 40 required to to qualify."
                            @if (isset($application))
                                value="{{old("english_mark_obtained", $application->english_mark_obtain)}}"
                            @else
                                value="{{old("english_mark_obtained")}}"
                            @endif 
                            >
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
    <div class="row">
        <div class="col-xs-12 text-right">
            {{-- <button type="button" class="btn btn-primary previous" id="previous"><i class="fa fa-arrow-left"></i> Previous</button> --}}
            <button type="submit" class="btn btn-success next">Submit & Update <i class="fa fa-save"></i></button>
        </div>
    </div>
</div>
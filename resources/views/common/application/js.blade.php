<script src="{{asset_public("js/jquery.steps.min.js")}}"></script>
<script>
    var bootstrapOptionsValidator = 
    {errorElement: "span",
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "invalid-feedback help-block" );
    
            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs            
            element.parents( ".form-group" ).addClass( "has-feedback" ).addClass('has-error');
            console.log(element.parents( ".form-group" ));
            if ( element.prop( "type" ) === "checkbox" ||  element.prop( "type" ) === "radio") {
                error.insertAfter( element.parent( "label" ).parent("div") );
            } else {
                error.insertAfter( element );
            }
            element.addClass('is-invalid');
    
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if ( !element.next( "span" )[ 0 ] ) {
                // $( "<span class='glyphicon glyphicon-remove form-control-feedback'></span>" ).insertAfter( element );
            }
        },
        success: function ( label, element ) {
            $(element).parents( ".form-group" ).removeClass( "has-error" );
            $(element).parents( ".form-group" ).addClass( "has-success" );
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if ( !$( element ).next( "span" )[ 0 ] ) {
                // $( "<span class='glyphicon glyphicon-ok form-control-feedback'></span>" ).insertAfter( $( element ) );
            }
            if ( $( element ).prop( "type" ) === "checkbox" ||  $( element ).prop( "type" ) === "radio") {
                $( element ).removeClass('is-invalid');
            }else
                $( element ).removeClass('is-invalid').addClass('is-valid');
        },
        highlight: function(element, errorClass, validClass) {
            if ($(element).closest('.input-group').length > 0) {
                $(element).closest('.form-group').removeClass('has-error').addClass('has-error');
            } else {
                if (element.type === "radio") {
                    this.findByName(element.name).addClass(errorClass).removeClass(validClass);
                } else {
                    $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
                    if ($(element).closest('form').hasClass('form-horizontal')) {
                        $(element).closest('.form-group > div[class^="col"]').find('i.fa').remove();
                        $(element).closest('.form-group > div[class^="col"]').append('<i class="fa fa-exclamation fa-lg form-control-feedback"></i>');
                    } else {
                        $(element).closest('.form-group').find('i.fa').remove();
                        $(element).closest('.form-group').append('<i class="fa fa-exclamation fa-lg form-control-feedback"></i>');
                    }
                }
            }
        },
        unhighlight: function(element, errorClass, validClass) {
            if ($(element).closest('.input-group').length > 0) {
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success');
            } else {
                if (element.type === "radio") {
                    this.findByName(element.name).removeClass(errorClass).addClass(validClass);
                } else {
                    if ($(element).closest('form').hasClass('form-horizontal')) {
                        $(element).closest('.form-group > div[class^="col"]').find('i.fa').remove();
                        $(element).closest('.form-group').removeClass('has-error has-feedback').addClass('has-success has-feedback');
                    } else {
                        $(element).closest('.form-group').removeClass('has-error has-feedback').addClass('has-success has-feedback');
                        $(element).closest('.form-group').find('i.fa').remove();
                        $(element).closest('.form-group').append('<i class="fa fa-check fa-lg form-control-feedback"></i>');
                    }
                }
            }
        },
        normalizer: function( value ) {
            return $.trim( value );
        },

    };
    $(document).ready(function(){
        var loc = window.location;
        var form_name = "";
        setEwsNcl()
        var check_date = '{{date("Y-m-d", strtotime(\App\Models\Application::$dob_compare_to."+1 days"))}}';
        if(loc.hash != ""){
            form_name = loc.hash.replace("#", "");
            $("form").removeClass("active");
            $("form[name='"+form_name+"']").addClass("active");
            console.log(form_name);
            $("#btn-steps a").removeClass("active");
            $("a[data-form='"+form_name+"']").addClass("active");
        }
        var $current_active = $("form.active");
        if($(".form-class").length > 1){
            $(".form-class").not($current_active).hide();
            $current_active.show();
        }
        // $("input[name='passport_photo'").rules("add", 
        // {            
        //     extension: "jpe?g|png",
        //     required: true,
        //     maxsize: 100000,
        //     "messages": {
        //         maxsize: "Max File Size is 100KB",
        //         extension: "Accept only jpg/jpeg file",
        //         required: "Passport Photo is required"
        //     }
        // });
        // $("input[name='signature'").rules("add", 
        // {            
        //     extension: "jpe?g|png",
        //     required: true,
        //     maxsize: 100000,
        //     "messages": {
        //         maxsize: "Max File Size is 100KB",
        //         extension: "Accept only jpg/jpeg file",
        //         required: "Signature Photo is required"
        //     }
        // });
        if($("form[name='step-one-update']").length == 0){
            $("form.application-form").validate(bootstrapOptionsValidator);
        }
        $("form[name='step-one-update']").validate(bootstrapOptionsValidator);
        $("form[name='step-two-update']").validate(bootstrapOptionsValidator);
        $("form[name='step-three-update']").validate(bootstrapOptionsValidator);
        $("form[name='step-four-update']").validate(bootstrapOptionsValidator);
        $(document).on("input",".present_address", function(){
            if($(this).val().trim() == ""){
                return true;
            }
            if ($("#address_same").is(":checked")) {
                var current_input_name = $(this).prop("name");
                console.log(current_input_name);
                current_input_name = current_input_name.replace('correspondence', 'permanent')
                $("input[name='"+current_input_name+"']").val($(this).val());
                $("input[name='"+current_input_name+"']").focus();
                $(this).focus();
            }
        });
        $(document).on("change","#address_same", function(){
            if ($("#address_same").is(":checked")) {
                $(".present_address").each(function(index, element){
                    if ($(this).val() != "") {
                        $(this).trigger("input");
                    }
                });
                $(".permanent_address").prop("readonly", true);
            }else
                $(".permanent_address").prop("readonly", false).val('');
        });
        $(document).on("change","input[name='signature']", function(){
            console.log("Image size checking.");
            var _URL = window.URL || window.webkitURL;
            var file, img;
            if ((file = this.files[0])) {
                img = new Image();
                img.onload = function() {
                    // alert(this.width + " " + this.height);

                    if (this.width > 200) {
                        $("input[name='signature']").val("");
                        alert("Image max width is 200px");
                        return false;
                    }
                    if (this.height > 150) {
                        $("input[name='signature']").val("");
                        alert("Image max height is 150px");
                        return false;
                    }
                };
                img.onerror = function() {
                    alert( "not a valid file: " + file.type);
                    $("input[name='signature']").val("");
                };
                img.src = _URL.createObjectURL(file);
            }
        });
        $(document).on("change","input[name='passport_photo']", function(){
            console.log("Image size checking.");
            var _URL = window.URL || window.webkitURL;
            var file, img;
            if ((file = this.files[0])) {
                img = new Image();
                img.onload = function() {
                    // alert(this.width + " " + this.height);

                    if (this.width > 200) {
                        $("input[name='passport_photo']").val("");
                        alert("Image max width is 200px");
                        return false;
                    }
                    if (this.height > 250) {
                        $("input[name='passport_photo']").val("");
                        alert("Image max height is 250px");
                        return false;
                    }
                };
                img.onerror = function() {
                    alert( "not a valid file: " + file.type);
                    $("input[name='passport_photo']").val("");
                };
                img.src = _URL.createObjectURL(file);
            }
        });
        $('input#dob').Zebra_DatePicker({ format : 'd-m-Y',readonly_element:false, direction: -1,
            onSelect: function() { 
                $(this).change();
                // console.log($(this).val());
            }
        });
        $('input.zebra').each(function(index, element){
            $(this).attr({
                "data-inputmask" : "'alias': 'dd-mm-yyyy'",
                "pattern": "(?:(?:0[1-9]|1[0-9]|2[0-9])-(?:0[1-9]|1[0-2])|(?:30)-(?:(?!02)(?:0[1-9]|1[0-2]))|(?:31)-(?:0[13578]|1[02]))-(?:19|20)[0-9]{2}"
            });
            $(this).inputmask();
        });
        $(":input").inputmask();
        $("#accept").change(function(){
            btnDisabled();
        });
        // $(document).on("input", "#anm_academic_fields input, #anm_academic_fields textarea", function(){
        //     // console.log("ANM is changing");
        //     changeRequired(this, "#anm_academic_fields input, #anm_academic_fields textarea");
        // });
        $(document).on("input", "#vocational_academic_fields input, #vocational_academic_fields textarea", function(){
            // console.log("ANM is changing");
            changeRequired(this, "#vocational_academic_fields input, #vocational_academic_fields textarea");
        });
        $(document).on("change", "#dob", function(){
            var age = 0;
            var $dob = $("#dob");
            if ($dob.valid() == true ) {
                age = dateDiffYear($dob.val(), check_date);
                $("#age").val(age);
            }else{
                console.log("date is false");
                $("#age").val(age);

            }
        });
        $(document).on("change", ".anm_or_lhv", function(){
            changeAnmLhvStatus(this);
        });
        $(document).on("change", "input[type='file']", function(){
            var file_name_array = [];
            var alert_data = "";
            $("input[type='file']").each(function(index, element){
                if($(element).val() != ""){
                    var file_name = $(element).val().split("\\").pop()
                    console.log(file_name_array.indexOf(file_name));
                    if(file_name_array.indexOf(file_name) != -1){
                        alert_data += $(element).prop("name");
                    }
                    file_name_array.push(file_name);                    
                }
            });
            
            console.log(file_name_array);
            console.log(alert_data);
            if(alert_data != ""){
                alert_data = alert_data+" File Names are Duplicate please verify duplicate file."
                alert(alert_data);
            }
        });
    });
    changeAnmLhvStatus = function(Obj){
        var $selectedAnmLhv = $(".anm_or_lhv:checked");
        if(parseInt($selectedAnmLhv.val())){
            $("#anm_or_lhv_registration").prop("readonly", false).val($("#anm_or_lhv_registration").data("old"));
        }else
            $("#anm_or_lhv_registration").prop("readonly", true).val("NA");
    }
    dateDiffYear = function(date_from, date_to){
        var date_from_array = date_from.split("-");
        var date_to_array   = date_to.split("-");
        dt1 = new Date(date_to_array[0],(date_to_array[1]-1),date_to_array[2]);
        dt2 = new Date(date_from_array[2],(date_from_array[1]-1),date_from_array[0]);
        console.log(dt1);
        console.log(dt2);
        // console.log(date_from+" - "+date_to);
        var diff =(dt2.getTime() - dt1.getTime()) / 1000;
        diff /= (60 * 60 * 24);
        return Math.abs(parseInt(diff/365.25));
    }
    changeRequired = function(Obj, row_id){
        var $current = $(Obj);
        var required_field = false;
        var $anm_fields = $(row_id);
        $anm_fields.each(function(){
            if($(this).val() != ""){
                required_field = true;
                return true;
            }
        });
        if(required_field){
            $anm_fields.prop("required", true);
        }else{
            $anm_fields.prop("required", false);
        }
    }
    changeVocationalRequired = function(Obj){
        var $current = $(Obj);
        var required_field = false;
        var $anm_fields = $("#vocational_academic_fields input");
        $anm_fields.each(function(){
            if($(this).val() != ""){
                required_field = true;
                return true;
            }
        });
        if(required_field){
            $anm_fields.prop("required", true);
        }else{
            $anm_fields.prop("required", false);
        }
    }
    btnDisabled = function(){
        if($("#accept").is(":checked")){
            $("#submit_btn").prop("disabled", false).removeClass("disabled");
            return true;
        }else
            $("#submit_btn").prop("disabled", true).addClass("disabled");
        return false;
    }
    setEwsNcl = function(){
        $(".ncl, .ews").hide(function(){
            $(this).find("input, select, textarea").prop({
                "disabled" : true
            });
        });
        var selected_caste = $("#caste").find("option:selected").text();
        if(selected_caste.trim().toLowerCase() == "general"){
            $(".ews").show(function(){
                $(this).find("input, select, textarea").prop({
                    "disabled" : false
                });
                checkCasteDocRequired();
            });
        }else if(selected_caste.trim().toLowerCase() == "obc/mobc"){
            $(".ncl").show(function(){
                $(this).find("input, select, textarea").prop({
                    "disabled" : false
                })
                
                checkCasteDocRequired();
            });
        }else{
            checkCasteDocRequired();
        }
    }
    $("#submit_btn").on("click", function(){
        if(!btnDisabled()){
            alert("Please Accept the Declaration to submit form.");
            return false;
        }
    });
    $(".ews, .ncl input").on("change", function(){
        checkCasteDocRequired();
    });
    checkCasteDocRequired = function(){
        $current =  $("#caste");
        console.log($("input[name='sub_cat']:checked:not([disabled])").length);
        var selected_caste = $current.find("option:selected").text();
        if(selected_caste.trim().toLowerCase() == "general" || selected_caste.trim().toLowerCase() == "obc/mobc"){
            console.log($("input[name='sub_cat']:checked").first().val());
            if($("input[name='sub_cat']:checked:not([disabled])").length){
                if($("input[name='sub_cat']:checked:not([disabled])").first().val() ==="NA" || $("input[name='sub_cat']:checked:not([disabled])").first().val() == ""){
                    $("input[name='caste_certificate']").prop({
                        "required": false,
                        "disabled": true,
                    });
                }else{
                    // $("input[name='caste_certificate']").prop("required", true);
                    $("input[name='caste_certificate']").prop({
                        "required": true,
                        "disabled": false,
                    });
                }
            }else{
                // $("input[name='caste_certificate']").prop("required", false);
                $("input[name='caste_certificate']").prop({
                    "required": false,
                    "disabled": true,
                });
            }
        }else
            $("input[name='caste_certificate']").prop("required", true);
    }
    $(document).on("change", "input[name='disablity']", function(){
        if($(this).is(":checked") && parseInt($(this).val())){
            $("input[name='disablity_certificate']").prop("required", true);
        }else
            $("input[name='disablity_certificate']").prop("required", false);
    });
    $("#caste").on("change", function(){
        setEwsNcl();
    });
    $("#btn-steps a").on("click", function(){
        var form_name = $(this).data("form");
        console.log(form_name);
        if($("form[name='"+form_name+"']").length == 0){
            return false;
        }
        $(".form-class").not($("form[name='"+form_name+"']")).removeClass("active").hide();
        $("form[name='"+form_name+"']").show(function(){
            $(this).addClass("active");
        });
        $("#btn-steps a").removeClass("btn-primary").removeClass("active").addClass("btn-info").addClass("btn-info");
        $(this).removeClass("btn-info").addClass("btn-primary").addClass("active");
    });
function scrollNav() {
  $('.form-nav a').click(function(){  
    //Toggle Class
    $(".active").removeClass("active");      
    $(this).closest('li').addClass("active");
    var theClass = $(this).attr("class");
    $('.'+theClass).parent('li').addClass('active');
    //Animate
    $('html, body').stop().animate({
        scrollTop: $( $(this).attr('href') ).offset().top - 160
    }, 400);
    return false;
  });
  $('.scrollTop a').scrollTop();
}
scrollNav();
</script>
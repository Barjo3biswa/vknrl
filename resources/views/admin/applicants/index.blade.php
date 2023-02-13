@extends('admin.layout.auth')
@section("content")
<div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Filter: </div>
                    <div class="panel-body">
                        <form action="" method="get">
                            @include('admin/applicants/filter')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
            <div class="panel-heading">Registered Applicant's: <strong>{{$applicants->total()}} records found</strong></div>
                <div class="panel-body">
                   @include("common.applicants.index")
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
$("button[type='reset']").on("click", function(){
    $(".filter input").attr("value", "").val("");
    $(".filter").find("select").each(function(index, element){
        $(element).find("option").each(function(){
            if (this.defaultSelected) {
                this.defaultSelected = false;
                // this.selected = false;
                $(element).val("").val("all");
                return;
            }
        });
    });
});
resetPassword = function(string){
    if(!confirm("Change Password ?")){
        return false;
    }
    var ajax_post =  $.post('{{route("admin.applicants.changepass")}}', {"_token" :'{{csrf_token()}}', 'user_id':string});
    ajax_post.done(function(response){
        alert(response.message);
    });
    ajax_post.fail(function(){
        alert("Failed Try again later.");
    });
}
</script>    
@endsection
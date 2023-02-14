{{-- <div class="alert alert-success" role="alert">
    <button type="button" class="close" data-dismiss="alert">×</button>	
    <p>You are logged in!</p>
</div> --}}
<div class="col-md-12" id="printableArea">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12" style="text-align: center">
                        VKNRL SCHOOL OF NURSING
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12" style="text-align: center">
                        REGIONAL CONFERENCE ON 10 th &amp; 11 th March 2023
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12" style="text-align: center">
                        Theme “LEADERSHIP SKILLS IN NURSING PRACTICE FROM GRASSROOTS TO GLOBAL LEVEL”
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-body">

            @if ($conference)
                @if ($conference->form_step == 'submited')
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-7">
                                <a href="{{ route(get_guard() . '.application.conferense.payment', Crypt::encrypt($conference->id)) }}"
                                    class="btn btn-primary" style="float:right"> Proceed To Payment</a>
                            </div>
                        </div>
                    </div>
                @elseif ($conference->form_step == 'payment_done')
                    <div class="form-group">
                        <div class="row">
                            <div class="row">
                                @include('common.conference.payment-receipt')
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <form action="{{ route(get_guard() . '.application.conferense.save') }}" method="post">
                    {{ csrf_field() }}
                    @include('common.conference.form')

                </form>
            @endif
        </div>
    </div>
</div>
<script>
    function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>

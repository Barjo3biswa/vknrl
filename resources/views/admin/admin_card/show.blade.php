@extends('admin.layout.auth')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Admit Card
                    <span class="pull-right"><a href="{{route("admin.admit-card.download.pdf", Crypt::encrypt($admit_card->id))}}"><button
                                class="btn btn-sm btn-primary"><i class="glyphicon glyphicon-download"></i> Download Admit Card</button></a></span>
                </div>
                <div class="panel-body">
                    @include('common/application/admit_card/admit_card')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
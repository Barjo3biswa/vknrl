<li class=""><a href="{{route("student.home")}}">Home</a></li>
@if (auth()->user()->category=='student')
    <li><a href="{{route("student.application.index")}}">Application</a></li>
    <li><a target="_blank" href="{{asset_public("help.pdf")}}" style="cursor:help;"><i class="fa fa-question-circle"></i> Help</a></li>
@endif
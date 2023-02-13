<li class=""><a href="{{route("admin.home")}}">Home</a></li>
<li><a href="{{route("admin.applicants.list")}}">Applicant's</a></li>
<li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
        aria-expanded="false">Reports <span class="caret"></span></a>
    <ul class="dropdown-menu">
        <li><a href="{{route("admin.application.index", ["status" => ""])}}">List of Application All</a></li>
        <li><a href="{{route("admin.application.index", ["status" => "application_submitted"])}}">Incomplete Applications</a></li>
        <li><a href="{{route("admin.application.index", ["status" => "payment_done"])}}">List of Application Submitted</a></li>
        <li><a href="{{route("admin.application.index", ["status" => "accepted"])}}">List of Application Accepted</a></li>
        <li><a href="{{route("admin.application.index", ["status" => "rejected"])}}">List of Application Rejected</a></li>
        <li><a href="{{route("admin.application.index", ["status" => "on_hold"])}}">List of Application Hold</a></li>
        <li><a href="{{route("admin.application.index", ["status" => "qualified"])}}">List of WT qualified Applicants</a></li>
    </ul>
</li>
<li><a href="{{route("admin.notification.index")}}">Notification's</a></li>
<li><a href="{{route("admin.exam-center.index")}}">Exam Center</a></li>
<li><a href="{{route("admin.admit-card.index")}}">Admit Card</a></li>
<li><a href="{{route("admin.application.upload.student.qualified")}}">Upload Qualified Student</a></li>
<li><a href="{{route("admin.log.index")}}">Daily Logs</a></li>
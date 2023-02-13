<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;

class ApplicationAttachmentController extends Controller
{
    private $request;
    private $student_id;
    private $application_id;
    private $url;
    private $directory = "uploads";
    public function show(Request $request, $student_id, $application_id, $url)
    {
        $this->request = $request;
        $this->student_id = $student_id;
        $this->application_id = $application_id;
        $this->url = $url;
        if (auth("admin")->check()) {
            return $this->response();
        } elseif (auth("student")->check()) {
            if ($student_id == auth("student")->id()) {
                return $this->response();
            }
        }
        $activity = "Accessing file of applicaiton id {$application_id}";
        saveLogs("0", "no auth", "no guard", $activity, false);
        abort(404);
    }
    private function response()
    {
        $fileName = $this->directory . "/" . $this->student_id . "/" . $this->application_id . "/" . $this->url;
        $storage_file = Storage::disk('uploads');
        if(!$storage_file->exists($fileName)){
            abort(404);
        }
        return $storage_file->response($fileName);
        return response()->stream(function () use ($fileName, $storage_file) {
            $stream = $storage_file->readStream($fileName);
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Type' => Storage::disk('uploads')->mimeType($fileName),
            'Content-Length' => Storage::disk('uploads')->size($fileName),
            'Content-Disposition' => 'attachment; filename="' . basename($fileName) . '"',
            'Pragma' => 'public',
        ]);
    }
}

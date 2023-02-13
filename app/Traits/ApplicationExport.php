<?php
namespace App\Traits;

use App\Models\Application;
use Excel;
use Log;
/**
 * Trait for handling Export (Applications)
 * date: 05-07-2019
 */
trait ApplicationExport
{
    public function ExportApplicationData($applications, $request)
    {
        $arr = [];
        foreach ($applications as $key => $application) {
            $arr[] = [
                "Sl. No."   => ($key+1),
                "Application No."   => $application->id,
                "Name of the Applicant"   => $application->fullname,
                "Father's Name"   => $application->father_name,
                "Address"   => $this->getApplicationPermanentAddress($application),
                "Contact No."   => $application->student->mobile_no,
                "D.O.B"   => dateFormat($application->dob),
                "Caste"   => $application->caste->name,
                "Stream"   => $application->academic_12_stream,
                "Total Marks Secured in (10+2) Exam"   => $application->academic_12_mark,
                "Percentage of Marks in (10+2) Exam"   => $application->academic_12_percentage,
                "Marks Obtained in English Language in (10+2) Exam"   => $application->english_mark_obtain,
                "Name of the School from where HSLC Passed"   =>$application->academic_10_school,
            ];
        }
        if(!$arr){
            return redirect()->back()->with("error", "Sorry records not found.");
        }
        Excel::create('Applications '.getActiveSession()->name, function ($excel) use ($arr) {
            $excel->sheet('Applications '.getActiveSession()->name, function ($sheet) use ($arr) {
                $sheet->setTitle('Applications');

                  $sheet->cells('A1:M1', function($cells) {
                    $cells->setFontWeight('bold');
                  });

                $sheet->fromArray($arr, null, 'A1', false, true);
            });
        })->download('xlsx');
    }
    public function getApplicationPermanentAddress($application) {
        return str_replace("</br>", "\n", getApplicationPermanentAddress($application));
    }

}

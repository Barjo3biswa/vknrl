<?php
namespace App\Traits;

use Illuminate\Http\Request;
use Log, Exception, DB, Excel;
/**
 * Trait for handling Export (Admit Card)
 * date: 19-07-2019
 */
trait ExportAdmitCard {
    public function exportAdmitCard($admit_card_list)
    {
        if(sizeof((array)$admit_card_list)){
            $arr = [];
            foreach($admit_card_list as $key => $admit_card){
                $arr[] = [
                    "Exam Center" => $admit_card->exam_center->center_name,
                    "Application No" => str_pad($admit_card->application_id, 4, "0000", STR_PAD_LEFT),
                    "Registration No" => str_pad($admit_card->application->student_id, 4, "0000", STR_PAD_LEFT),
                ];
            }
            Excel::create('Admit Card '.getActiveSession()->name." ".date("YmdHis"), function ($excel) use ($arr) {
                $excel->sheet('Admit Card '.getActiveSession()->name, function ($sheet) use ($arr) {
                    $sheet->setTitle('Admit Card '.date("YmdHis"));
    
                      $sheet->cells('A1:M1', function($cells) {
                        $cells->setFontWeight('bold');
                      });
    
                    $sheet->fromArray($arr, null, 'A1', false, true);
                });
            })->download('xlsx');
        }
        return "Sorry No Data Found.";
    }
}
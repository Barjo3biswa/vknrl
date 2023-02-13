<?php
namespace App\Traits;

use App\Models\Application;
use PDF;
use Log;
/**
 * Trait for handling Download (Applications)
 * date: 06-07-2019
 */
trait ApplicationDownloader
{
    public function downloadApplicationAsPDF(Application $application) {
        // return view("common.application.download", compact("application"));
        $pdf = PDF::loadView("common.application.download", compact("application"));
        return $pdf->download($application->id.'.pdf');
    }

}
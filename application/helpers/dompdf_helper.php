<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function pdf_create($html, $filename='', $stream=TRUE, $pReportDisplayOptions) 
{
    //echo "HTML: " . $html . "<br />";
    //echo "END HTML";

    $pdfOptions = new \Dompdf\Options();
    $pdfOptions->set('defaultFont', 'Courier');
    $pdfOptions->set('dpi', $pReportDisplayOptions->getPDFResolution());

    $dompdf = new \Dompdf\Dompdf($pdfOptions);
    //$dompdf = new \Dompdf\Dompdf();


    //Testing layout to fit on one page
    //$dompdf->setOption('dpi', $pReportDisplayOptions->getPDFResolution());
    
    $dompdf->loadHtml($html);

    $dompdf->setPaper($pReportDisplayOptions->getPDFPaperSize(), $pReportDisplayOptions->getPDFOrientation());

    //$dompdf->setOption('isHtml5ParserEnabled', true);

    gc_disable();
    $dompdf->render();
    gc_enable();
    //exit();

    if ($stream) {
        $dompdf->stream($filename.".pdf");
    } else {
        return $dompdf->output();
    }
}
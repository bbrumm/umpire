<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function pdf_create($html, $filename='', $stream=TRUE, $DPI) 
{
    require_once("dompdf/dompdf_config.inc.php");
    $dompdf = new DOMPDF();
    //Testing layout to fit on one page
    $dompdf->set_option('dpi', $DPI);
    
    $dompdf->load_html($html);
    $dompdf->set_paper('a3', 'landscape');
    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename.".pdf");
    } else {
        return $dompdf->output();
    }
}
?>
<?php

namespace App\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
   private $domPdf;

public function __construct()
{
    
$this->domPdf = new Dompdf();
$pdfOptions = new Options();
$pdfOptions->set('defaulFont','Garamond');
$this->domPdf->setOptions($pdfOptions);

}


public function showPdfFile($html)
{
   $this->domPdf->loadHtml($html);
   $this->domPdf->render();
   $this->domPdf->stream("details.pdf",[
    'Attachment'=> false
   ]);
}


public function generateBinaryPdf($html)
{
   $this->domPdf->loadHtml($html);
   $this->domPdf->render();
   $this->domPdf->output();
    
}




   
}
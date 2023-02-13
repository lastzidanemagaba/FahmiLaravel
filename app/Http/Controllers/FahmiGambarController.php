<?php

namespace App\Http\Controllers;
use setasign\Fpdi\Fpdi; 



class FahmiGambarController extends Controller
{
    protected $pdf;
 
    public function __construct()
    {
        $this->pdf = new Fpdi;
    }

    public function index() 
    {
        // Source file and watermark config 
        $file = 'sample.pdf'; 
        $text_image = 'de.png'; 
        
        // Set source PDF file 
        $pdf = new Fpdi(); 
        if(file_exists("./".$file)){ 
            $pagecount = $this->pdf->setSourceFile($file); 
        }else{ 
            die('Source PDF not found!'); 
        } 
        
        // Add watermark image to PDF pages 
        for($i=1;$i<=$pagecount;$i++){ 
            $tpl = $this->pdf->importPage($i); 
            $size = $this->pdf->getTemplateSize($tpl); 
            $this->pdf->addPage(); 
            $this->pdf->useTemplate($tpl, 1, 1, $size['width'], $size['height'], TRUE); 
            
            //Put the watermark 
            $xxx_final = ($size['width']-60); 
            $yyy_final = ($size['height']-25); 
            $this->pdf->Image($text_image, $xxx_final, $yyy_final, 0, 0, 'png'); 
        } 
        

        

        
        $this->pdf->Output();

        exit;
    }

}
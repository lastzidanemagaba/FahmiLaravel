<?php

namespace App\Http\Controllers;
use setasign\Fpdi\Fpdi; 



class FahmiController extends Controller
{
    protected $pdf;
 
    public function __construct()
    {
        $this->pdf = new Fpdi;
    }

    public function index() 
    {
        $text = 'FAHMI ASU'; 
        $file = 'sample.pdf';
        $name = uniqid(); 
        $font_size = 5; 
        $opacity = 100; 
        $ts = explode("\n", $text); 
        $width = 0; 
        foreach($ts as $k=>$string){ 
            $width = max($width, strlen($string)); 
        } 
        $width  = imagefontwidth($font_size)*$width; 
        $height = imagefontheight($font_size)*count($ts); 
        $el = imagefontheight($font_size); 
        $em = imagefontwidth($font_size); 
        $img = imagecreatetruecolor($width, $height); 
        
        // Background color 
        $bg = imagecolorallocate($img, 255, 255, 255); 
        imagefilledrectangle($img, 0, 0, $width, $height, $bg); 
        
        // Font color settings 
        $color = imagecolorallocate($img, 0, 0, 0); 
        foreach($ts as $k=>$string){ 
            $len = strlen($string); 
            $ypos = 0; 
            for($i=0;$i<$len;$i++){ 
                $xpos = $i * $em; 
                $ypos = $k * $el; 
                imagechar($img, $font_size, $xpos, $ypos, $string, $color); 
                $string = substr($string, 1);       
            } 
        } 
        imagecolortransparent($img, $bg); 
        $blank = imagecreatetruecolor($width, $height); 
        $tbg = imagecolorallocate($blank, 255, 255, 255); 
        imagefilledrectangle($blank, 0, 0, $width, $height, $tbg); 
        imagecolortransparent($blank, $tbg); 
        $op = !empty($opacity)?$opacity:100; 
        if ( ($op < 0) OR ($op >100) ){ 
            $op = 100; 
        } 
        
        // Create watermark image 
        imagecopymerge($blank, $img, 0, 0, 0, 0, $width, $height, $op); 
        imagepng($blank, $name.".png"); 
        
        if(file_exists("./".$file)){ 
            $pagecount = $this->pdf->setSourceFile($file); 
        }else{ 
            die('Source PDF not found!'); 
        } 
        
        // Add watermark to PDF pages 
        for($i=1;$i<=$pagecount;$i++){ 
            $tpl = $this->pdf->importPage($i); 
            $size = $this->pdf->getTemplateSize($tpl); 
            $this->pdf->addPage(); 
            $this->pdf->useTemplate($tpl, 1, 1, $size['width'], $size['height'], TRUE); 
            
            //Put the watermark 
            $xxx_final = ($size['width']-50); 
            $yyy_final = ($size['height']-25); 
            $this->pdf->Image($name.'.png', $xxx_final, $yyy_final, 0, 0, 'png'); 
        } 
        @unlink($name.'.png'); 
        

        
        $this->pdf->Output();

        exit;
    }

}
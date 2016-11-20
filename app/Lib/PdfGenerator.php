<?php

namespace App\Lib;
use Illuminate\Support\Facades\Log;
use Monolog\Logger ;
use Illuminate\Http\Request;
use PDFlib;
class PdfGenerator 
{
    public $pdfObject = null;
    public $font = null;
    public $buffer = null;
    public $location = "/test";
    public $dir = "1";
    private $_log = null;

    public function __construct() {        
        $this->_log = \Log::getMonolog();
    }
    
    public function init() {
       
        try {
            $this->pdfObject = new PDFlib();
            # This means we must check return values of load_font() etc.
            $this->pdfObject->set_option("errorpolicy=return");

            /* Enable the following line if you experience crashes on OS X
             * (see PDFlib-in-PHP-HowTo.pdf for details):
             * $this->pdfObject->set_option("usehostfonts=false");
             */

            /* all strings are expected as utf8 */
            $this->pdfObject->set_option("stringformat=utf8");

            /*  open new PDF file; insert a file name to create the PDF on disk */
            if ($this->pdfObject->begin_document("", "") == 0) {
                $this->_log->addInfo("Error: " . $this->pdfObject->get_errmsg());
                die("Error: " . $this->pdfObject->get_errmsg());
            }
        } catch (PDFlibException $e) {
            $error = "PDFlib exception occurred :\n" .
                    "[" . $e->get_errnum() . "] " . $e->get_apiname() . ": " .
                    $e->get_errmsg() . "\n";
            $this->_log->addInfo($error);
            die($error);
        } catch (Exception $e) {
            $this->_log->addInfo($e);
            die($e);
            die($e);
        }
    }

    public function setDocument($docWidth, $docHeight) {

        if (!isset($docHeight) || !isset($docWidth)){
            return;
        }
        $this->pdfObject->begin_page_ext($docHeight, $docWidth, "");
    }

    public function setFonts() {
        try {
            $this->font = $this->pdfObject->load_font(
                    "Helvetica-Bold",
                    "unicode",
                    "");
            if ($this->font == 0) {
                die("Error: " . $this->pdfObject->get_errmsg());
            }
            $this->pdfObject->setfont($this->font, 24.0);
        } catch (PDFlibException $e) {
            $error = "PDFlib exception occurred :\n" .
                    "[" . $e->get_errnum() . "] " . $e->get_apiname() . ": " .
                    $e->get_errmsg() . "\n";
            $this->_log->addInfo($error);
            die($error);
        } catch (Exception $e) {
            $this->_log->addInfo($e);
            die($e);
        }
    }

    public function setColor($fill = "fill", $color = "rgb", $hex_color) {
        if (!isset($hex_color) || $hex_color == NULL) {
            return false;
        }
        $rgb = [];
        $rgb = $this->_hex2rgb($hex_color);
        $rgb2PdfLibSuitable = $this->_convertAsSuitsLib($rgb);

        $this->pdfObject->setcolor(
                $fill,
                $color,
                $rgb2PdfLibSuitable[0],
                $rgb2PdfLibSuitable[1],
                $rgb2PdfLibSuitable[2],
                0);
    }

    public function colorConvert($hex_color) {
        if (!isset($hex_color) || $hex_color == NULL){
          return false;
        }
        $rgb = [];
        $rgb = $this->_hex2rgb($hex_color);
        $rgb2PdfLibSuitable = $this->_convertAsSuitsLib($rgb);
        return $rgb2PdfLibSuitable[0] .
                " " . $rgb2PdfLibSuitable[1] .
                " " . $rgb2PdfLibSuitable[2];
    }

    private function _convertAsSuitsLib($rgb) {
        $convertedRgb = [];
        foreach ($rgb as $value) {
            $convertedRgb[] = number_format(
                    $this->_convertAsSuitsLibHelper($value), 2);
        }
        return $convertedRgb;
    }

    private function _convertAsSuitsLibHelper($rgbParticle) {
        try {
            return $result = $rgbParticle / 255;
        } catch (Exception $e) {
            $this->_log->addInfo($e);
            return $result = 0;
        }
    }

    public function setTextPostion(
            $left,
            $top,
            $width,
            $height,
            $documnet,
            $hexColor,
            $showText = "no Text"
            ) {
        try {
            $optionList = " fontname=Helvetica fontsize=10 encoding=unicode " .
                    "fillcolor={rgb " . $this->colorConvert($hexColor) .
                    "} alignment=justify";
            $tf = $this->pdfObject->add_textflow(0, $showText, $optionList);
            if ($tf == 0) {
                $error = "Error: " . $this->pdfObject->get_errmsg();
                $this->_log->addInfo($error);
                die($error);
            }
            $optlist = "verticalalign=justify linespreadlimit=100% ";
            $this->pdfObject->fit_textflow(
                    $tf, $left, //x1
                    $documnet['height'] - $top, //y1
                    $width + $left, //x2
                    $documnet['height'] - ($height + $top), //y2
                    $optlist);
        } catch (PDFlibException $e) {
             $error = "PDFlib exception occurred :\n" .
                    "[" . $e->get_errnum() . "] " . $e->get_apiname() . ": " .
                    $e->get_errmsg() . "\n";
                $this->_log->addInfo($error);
            die($error);
        } catch (Exception $e) {
            $this->_log->addInfo($error);
            die($e);
        }
    }

    public function endDocument() {
        try {
            $this->pdfObject->end_page_ext("");
            $this->pdfObject->end_document("");
        } catch (PDFlibException $e) {
             $error = "PDFlib exception occurred :\n" .
                    "[" . $e->get_errnum() . "] " . $e->get_apiname() . ": " .
                    $e->get_errmsg() . "\n";
                $this->_log->addInfo($error);
            die($error);
        } catch (Exception $e) {
            $this->_log->addInfo($error);
            die($e);
        }
    }

    public function getPdfFromBuffer() {
        try {
            $this->buffer = $this->pdfObject->get_buffer();
            return strlen($this->buffer);
        } catch (PDFlibException $e) {
             $error = "PDFlib exception occurred :\n" .
                    "[" . $e->get_errnum() . "] " . $e->get_apiname() . ": " .
                    $e->get_errmsg() . "\n";
                $this->_log->addInfo($error);
            die($error);
        } catch (Exception $e) {
            die($e);
        }
    }

    public function setHeaders($OutputFileName = "hello.pdf") {
        header("Content-type: application/pdf");
        header("Content-Length: " . $this->getPdfFromBuffer());
        header("Content-Disposition: inline; filename=" . $OutputFileName);
    }

    public function screening() {
        print $this->buffer;
        $this->pdfObject = null;
    }

    private function _hex2rgb($hex_color) {
        $values = str_replace('#', '', $hex_color);
        switch (strlen($values)) {
            case 3;
                list( $r, $g, $b ) = sscanf($values, "%1s%1s%1s");
                return [ hexdec("$r$r"), hexdec("$g$g"), hexdec("$b$b")];
            case 6;
                return array_map('hexdec', sscanf($values, "%2s%2s%2s"));
            default:
                return false;
        }
    }

    public function setImage($imageContainer, $document) {
        if (!isset($imageContainer) || empty($imageContainer)){
            return;
        }
        $searchpath =  public_path() .
                $this->location .
                "/". $this->dir .
                "/";
        
        $this->pdfObject->set_option("SearchPath={{" .
                $searchpath .
                "}}");

        $image = $this->pdfObject->load_image("auto",
                $imageContainer['uri'],
                "");
        if ($image == 0) {
            die("Error: " . $this->pdfObject->get_errmsg());
        }

         $buf = "boxsize={" . 
                $imageContainer['position']['width'] . 
                " " . 
                $imageContainer['position']['height'] .
                "} position={right top} fitmethod=clip";
        $this->pdfObject->fit_image($image,
                $imageContainer['position']['left'],
                $document['height'] 
                - ($imageContainer['position']['height'] 
                + $imageContainer['position']['top']),
                $buf);       
    }
    
    public function find_dirs() {
        $dir = public_path() .
                $this->location .
                "/";
        $result = array();
        $cdir = scandir($dir);
        foreach ($cdir as $value) {
            if (!in_array($value, array(".", ".."))) {
                if (is_dir($dir . "/" . $value)) {
                    $result[] = $value;
                } else {
                    $result[] = $value;
                }
            }
        }
        return $result;
    }

}

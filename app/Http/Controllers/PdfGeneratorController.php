<?php
/**
 * Class PdfGeneratorController uses lib PdfGenerator
 * All logic and generation functionality is located in PdfGenerator.
 * 
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lib\PdfGenerator;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class PdfGeneratorController extends Controller {

    private $_PdfGeneratorObject = "";
    private $_dirLocation = "/test";
    private $_document = ["height" => 0, "width" => 0];

    public function __construct() {
        $this->_PdfGeneratorObject = new PdfGenerator();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $this->_PdfGeneratorObject->init();
        $this->_PdfGeneratorObject->dir = (string) $request->documentName;
        $this->_PdfGeneratorObject->location = $this->_dirLocation;
        $path = "." .
                $this->_dirLocation . "/" .
                $this->_PdfGeneratorObject->dir .
                "/data.json";
        $string = file_get_contents($path);
        $json = json_decode($string, true);
        $this->_documentCollecter($json);
        $this->_PdfGeneratorObject->endDocument();
        $this->_PdfGeneratorObject->setHeaders();
        $this->_PdfGeneratorObject->screening();
        exit;
    }
    
    private function _documentCollecter($json) {
        foreach ($json as $key => $value) {
            switch ($key) {
                case "document":
                    $this->_documentHelper($json, $key);
                    break;
                case "images":
                    $this->_imagesHelper($value);
                    break;
                case "texts":
                    $this->_textsHelper($value);
                    break;
            }
        }
    }
    private function _documentHelper($json, $key) {
        $this->_document = ["height" => $json[$key]['height'],
            "width" => $json[$key]['width']];
        
        $this->_PdfGeneratorObject->setDocument($json[$key]['height'],
                $json[$key]['width']);

        $this->_PdfGeneratorObject->setFonts();
    }

    private function _imagesHelper($value) {
        foreach ($value as $imageValue) {
            $this->_PdfGeneratorObject->setImage($imageValue, $this->_document);
        }
    }

    private function _textsHelper($value) {
        foreach ($value as $nestedKey => $nestedValue) {
            $this->_PdfGeneratorObject->setColor(
                    "fill", "rgb", $nestedValue['color']
            );

            $this->_PdfGeneratorObject->setTextPostion(
                    $nestedValue['position']['left'],
                    $nestedValue['position']['top'],
                    $nestedValue['position']['width'],
                    $nestedValue['position']['height'],
                    $this->_document,
                    $nestedValue['color'],
                    $nestedValue['text']
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function listDirs() {
        $response = $this->_PdfGeneratorObject->find_dirs();
        if ($response) {
            return response()->json($response, 200);
        } else {
            return response()->json(['error' => "not such record to list"], 409);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}

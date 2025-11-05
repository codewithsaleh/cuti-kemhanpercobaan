<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Include the main PHPExcel class
require_once APPPATH . 'third_party/PHPExcel/Classes/PHPExcel.php';

class CI_PHPExcel {
    
    public function __construct() {
        log_message('Debug', 'PHPExcel class is loaded.');
    }
    
    public function load($file_path) {
        return PHPExcel_IOFactory::load($file_path);
    }
    
    public function create() {
        return new PHPExcel();
    }
    
    public function createWriter($objPHPExcel, $writerType = 'Excel2007') {
        return PHPExcel_IOFactory::createWriter($objPHPExcel, $writerType);
    }
    
    public function createReader($readerType = 'Excel2007') {
        return PHPExcel_IOFactory::createReader($readerType);
    }
}
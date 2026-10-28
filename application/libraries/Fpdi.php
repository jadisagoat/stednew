<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

// Ensure the path to FPDI and FPDF is correct
require_once APPPATH . 'third_party/setasign/Fpdi/src/autoload.php';
require_once APPPATH . 'libraries/Fpdf.php';

use setasign\Fpdi\Fpdi as BaseFpdi;

class Fpdi extends BaseFpdi
{
    // Custom methods if needed
}
